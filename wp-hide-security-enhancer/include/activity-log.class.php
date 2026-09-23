<?php

    if ( ! defined( 'ABSPATH' ) ) exit;

    /**
     * Lightweight, local audit trail for WP Hide security events.
     *
     * Request IP addresses intentionally come from REMOTE_ADDR only. Hosts that
     * terminate trusted proxies can opt in to a different source through the
     * wp-hide/activity_log/client_ip filter.
     */
    class WPH_activity_log
        {
            var $table_name;
            var $two_factor_failure_logged = FALSE;
            
            var $functions;

            function __construct()
                {
                    global $wph;                   
                    $this->functions    =   new WPH_functions();
                    
                    global $wpdb;
                    $this->table_name = $wpdb->prefix . 'wph_activity_log';

                    add_action( 'init',                             array( $this, 'init' ), 1 );
                    add_action( 'wp_login',                         array( $this, 'log_login_success' ), 10, 2 );
                    add_action( 'wp_login_failed',                  array( $this, 'log_login_failed' ), 10, 2 );
                    add_action( 'wp-hide/2fa/failed',               array( $this, 'log_two_factor_failure' ), 10, 3 );
                    add_action( 'wp-hide/2fa/user_authenticated',   array( $this, 'log_two_factor_success' ), 10, 2 );
                    add_action( 'wph/settings_changed',             array( $this, 'log_settings_changed' ), 20, 2 );
                    add_action( 'wph_activity_log_purge',           array( $this, 'purge_expired' ) );
                }

            function init()
                {
                    $this->maybe_create_table();

                    if ( ! wp_next_scheduled( 'wph_activity_log_purge' ) )
                        wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'wph_activity_log_purge' );
                }

            function maybe_create_table()
                {
                    global $wpdb;

                    $installed = get_option( 'wph_activity_log_db_version' );
                    if ( $installed === '1' )
                        return;

                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    $charset_collate = $wpdb->get_charset_collate();
                    $sql = "CREATE TABLE {$this->table_name} (
                                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                blog_id bigint(20) unsigned NOT NULL DEFAULT 0,
                                occurred_at datetime NOT NULL,
                                event_type varchar(64) NOT NULL,
                                severity varchar(16) NOT NULL DEFAULT 'info',
                                user_id bigint(20) unsigned NOT NULL DEFAULT 0,
                                username varchar(191) NOT NULL DEFAULT '',
                                ip_address varchar(45) NOT NULL DEFAULT '',
                                user_agent text NOT NULL,
                                request_uri text NOT NULL,
                                details longtext NOT NULL,
                                PRIMARY KEY  (id),
                                KEY occurred_at (occurred_at),
                                KEY event_type (event_type),
                                KEY user_id (user_id),
                                KEY ip_address (ip_address)
                            ) $charset_collate;";

                    dbDelta( $sql );
                    update_option( 'wph_activity_log_db_version', '1', FALSE );
                }

            function get_settings()
                {
                    $defaults = array(
                                        'retention_days' => 90,
                                        'alert_email'    => get_option( 'admin_email' ),
                                        'alert_events'   => array( 'login_failed', 'two_factor_failed' )
                                    );
                    $wph_settings   = $this->functions->get_settings();
                    $settings       = isset( $wph_settings['activity_log_settings'] ) && is_array( $wph_settings['activity_log_settings'] ) ? $wph_settings['activity_log_settings'] : array();

                    return wp_parse_args( is_array( $settings ) ? $settings : array(), $defaults );
                }

            function save_settings( $settings )
                {
                    $settings['retention_days'] = min( 3650, max( 1, absint( $settings['retention_days'] ) ) );
                    $settings['alert_email']    = sanitize_text_field( $settings['alert_email'] );
                    $allowed_events             = $this->get_event_labels();
                    $settings['alert_events']   = isset( $settings['alert_events'] ) && is_array( $settings['alert_events'] ) ? array_values( array_intersect( $settings['alert_events'], array_keys( $allowed_events ) ) ) : array();

                    $wph_settings               = $this->functions->get_settings();
                    $wph_settings['activity_log_settings'] = $settings;
                    $this->functions->update_settings( $wph_settings );

                    global $wph;
                    if ( isset( $wph ) && is_object( $wph ) )
                        $wph->settings = $wph_settings;
                }

            function get_event_labels()
                {
                    return array(
                                'login_success'      => __( 'Successful login', 'wp-hide-security-enhancer' ),
                                'login_failed'       => __( 'Failed login', 'wp-hide-security-enhancer' ),
                                'two_factor_success' => __( 'Two-factor authentication completed', 'wp-hide-security-enhancer' ),
                                'two_factor_failed'  => __( 'Two-factor authentication failed', 'wp-hide-security-enhancer' ),
                                'blocked_request'    => __( 'Blocked WP Hide request', 'wp-hide-security-enhancer' ),
                                'settings_changed'   => __( 'WP Hide settings changed', 'wp-hide-security-enhancer' )
                            );
                }

            function get_client_ip()
                {
                    $ip = isset( $_SERVER['REMOTE_ADDR'] ) && is_string( $_SERVER['REMOTE_ADDR'] )
                        ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
                        : '';

                    $ip = apply_filters( 'wp-hide/activity_log/client_ip', $ip );
                    $ip = is_string( $ip ) ? filter_var( $ip, FILTER_VALIDATE_IP ) : FALSE;

                    return $ip ? $ip : '';
                }
                
            function sanitize_request_uri( $request_uri )
                {
                    if ( ! is_string( $request_uri ) )
                        return '';

                    $request_uri = wp_unslash( $request_uri );

                    $sensitive_parameters = array(
                        'token',
                        'access_token',
                        'auth_token',
                        'authorization',
                        'password',
                        'passwd',
                        'pwd',
                        'secret',
                        'api_key',
                        'apikey',
                        'signature',
                        'sig',
                        'nonce',
                        '_wpnonce',
                        'code'
                    );

                    $pattern = '/([?&](?:' . implode( '|', array_map( 'preg_quote', $sensitive_parameters ) ) . ')=)[^&#]*/i';

                    $request_uri = preg_replace( $pattern, '$1[REDACTED]', $request_uri );

                    return substr( esc_url_raw( $request_uri ), 0, 2000 );
                }

            function log( $event_type, $severity = 'info', $args = array() )
                {
                    global $wpdb;

                    $this->maybe_create_table();
                    $current_user   = wp_get_current_user();
                    $user_id        = isset( $args['user_id'] )     ? absint( $args['user_id'] ) : ( $current_user instanceof WP_User ? $current_user->ID : 0 );
                    $username       = isset( $args['username'] )    ? sanitize_user( $args['username'], TRUE ) : ( $current_user instanceof WP_User ? $current_user->user_login : '' );
                    $details = isset( $args['details'] ) ? $args['details'] : array();

                    if ( ! is_array( $details ) )
                        $details = array( 'message' => (string) $details );
                        
                    $details_json = wp_json_encode( $details );

                    if ( false === $details_json )
                        $details_json = '{}';
                        
                    if ( strlen( $details_json ) > 32768 )
                        $details_json = wp_json_encode( array( 'message' => 'Event details exceeded the maximum allowed size.' ) );

                    $data = array(
                                    'blog_id'     => is_multisite() ? get_current_blog_id() : 0,
                                    'occurred_at' => current_time( 'mysql', TRUE ),
                                    'event_type'  => sanitize_key( $event_type ),
                                    'severity'    => in_array( $severity, array( 'info', 'warning', 'critical' ), TRUE ) ? $severity : 'info',
                                    'user_id'     => $user_id,
                                    'username'    => substr( $username, 0, 191 ),
                                    'ip_address'  => $this->get_client_ip(),
                                    'user_agent'  => isset( $_SERVER['HTTP_USER_AGENT'] ) && is_string( $_SERVER['HTTP_USER_AGENT'] )
                                        ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 1000 )
                                        : '',

                                    'request_uri' => isset( $_SERVER['REQUEST_URI'] ) && is_string( $_SERVER['REQUEST_URI'] )
                                                        ? $this->sanitize_request_uri( $_SERVER['REQUEST_URI'] )
                                                        : '',

                                    'details'     => $details_json
                                );

                    $result = $wpdb->insert( $this->table_name, $data, array( '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s' ) );
                    if ( $result )
                        $this->maybe_send_alert( $data );

                    do_action( 'wp-hide/activity_log/recorded', $event_type, $data, $result );
                    
                    return $result;
                }

            function log_login_success( $user_login, $user )
                {
                    $this->log( 'login_success', 'info', array( 'user_id' => $user instanceof WP_User ? $user->ID : 0, 'username' => $user_login ) );
                }

            function log_login_failed( $username, $error = NULL )
                {
                    if ( $this->two_factor_failure_logged )
                        return;

                    $details = array();
                    if ( is_wp_error( $error ) )
                        $details['error_code'] = $error->get_error_code();

                    $this->log( 'login_failed', 'warning', array( 'username' => $username, 'details' => $details ) );
                }

            function log_two_factor_failure( $user, $method, $error = NULL )
                {
                    $this->two_factor_failure_logged = TRUE;
                    $details = array( 'method' => sanitize_key( $method ) );
                    if ( is_wp_error( $error ) )
                        $details['error_code'] = $error->get_error_code();

                    $this->log( 'two_factor_failed', 'warning', array( 'user_id' => $user instanceof WP_User ? $user->ID : 0, 'username' => $user instanceof WP_User ? $user->user_login : '', 'details' => $details ) );
                }

            function log_two_factor_success( $user_id, $method )
                {
                    $user = get_userdata( $user_id );
                    $this->log( 'two_factor_success', 'info', array( 'user_id' => $user_id, 'username' => $user ? $user->user_login : '', 'details' => array( 'method' => sanitize_key( $method ) ) ) );
                }

            function log_settings_changed( $screen_slug = NULL, $tab_slug = NULL )
                {
                    if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
                        return;

                    $this->log( 'settings_changed', 'info', array( 'details' => array( 'screen' => sanitize_key( $screen_slug ), 'component' => sanitize_key( $tab_slug ) ) ) );
                }

            function log_blocked_request( $request_uri = '' )
                {
                    $throttle_key = 'wph_activity_blocked_' . md5( $this->get_client_ip() );

                    if ( get_transient( $throttle_key ) )
                        return;

                    set_transient( $throttle_key, 1, MINUTE_IN_SECONDS );

                    $this->log( 'blocked_request', 'warning', array(
                        'details' => array(
                            'blocked_uri' => $request_uri ? $this->sanitize_request_uri( $request_uri ) : ''
                        )
                    ) );
                }

            function maybe_send_alert( $data )
                {
                    $settings = $this->get_settings();
                    if ( empty( $settings['alert_email'] ) || ! in_array( $data['event_type'], $settings['alert_events'], TRUE ) )
                        return;

                    $throttle_key = 'wph_activity_alert_' . md5( $data['event_type'] . '|' . $data['ip_address'] );
                    if ( get_transient( $throttle_key ) )
                        return;

                    set_transient( $throttle_key, 1, 5 * MINUTE_IN_SECONDS );
                        
                    $labels = $this->get_event_labels();
                    $subject    = sprintf( '[%s] %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), isset( $labels[ $data['event_type'] ] ) ? $labels[ $data['event_type'] ] : $data['event_type'] );
                    $message    = sprintf( "Event: %s\nUser: %s\nIP: %s\nURL: %s\nTime (UTC): %s\n\nView all events: %s", $subject, $data['username'] ? $data['username'] : '-', $data['ip_address'] ? $data['ip_address'] : '-', $data['request_uri'] ? $data['request_uri'] : '-', $data['occurred_at'], admin_url( 'admin.php?page=wp-hide-logs' ) );
                    $recipients = array_filter( array_map( 'trim', explode( ',', $settings['alert_email'] ) ), 'is_email' );

                    if ( ! empty( $recipients ) )
                        wp_mail( $recipients, $subject, $message );

                    
                }

            function purge_expired()
                {
                    global $wpdb;
                    $settings = $this->get_settings();
                    $cutoff = gmdate( 'Y-m-d H:i:s', time() - ( absint( $settings['retention_days'] ) * DAY_IN_SECONDS ) );
                    $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->table_name} WHERE occurred_at < %s", $cutoff ) );
                }

            static function deactivate()
                {
                    wp_clear_scheduled_hook( 'wph_activity_log_purge' );
                }
        }

?>

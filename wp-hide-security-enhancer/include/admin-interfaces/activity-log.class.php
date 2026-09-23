<?php

    if ( ! defined( 'ABSPATH' ) ) exit;

    class WPH_activity_log_interface
        {
            var $logger;
            
            var $functions;

            function __construct( $logger )
                {
                    global $wph;                   
                    $this->functions    =   new WPH_functions();
                    
                    $this->logger = $logger;
                    add_action( 'admin_init', array( $this, 'handle_actions' ) );
                }

            function admin_print_styles()
                {
                    wp_register_style( 'tipsy.css', WPH_URL . '/assets/css/tipsy.css', array(), WPH_CORE_VERSION );
                    wp_enqueue_style( 'tipsy.css' );
                    wp_register_style( 'WPHStyle', WPH_URL . '/assets/css/wph.css', array(), WPH_CORE_VERSION );
                    wp_enqueue_style( 'WPHStyle' );
                    wp_register_style( 'wph-scan', WPH_URL . '/assets/css/wph-scan.css', array(), WPH_CORE_VERSION );
                    wp_enqueue_style( 'wph-scan' );
                    wp_enqueue_style( 'wph-logs', WPH_URL . '/assets/css/wph-logs.css', array( 'wph-scan' ), WPH_CORE_VERSION );
                }

            function admin_print_scripts()
                {
                    wp_enqueue_script( 'jquery' );
                }

            function handle_actions()
                {
                    if ( ! is_admin() || ! current_user_can( 'manage_options' ) || ! isset( $_GET['page'] ) || $_GET['page'] !== 'wp-hide-logs' )
                        return;

                    if ( isset( $_POST['wph_logs_settings'] ) )
                        {
                            check_admin_referer( 'wph_logs_settings' );
                            
                            $retention_days = isset( $_POST['retention_days'] ) && is_scalar( $_POST['retention_days'] )
                                ? absint( wp_unslash( $_POST['retention_days'] ) )
                                : 90;

                            $alert_email = isset( $_POST['alert_email'] ) && is_string( $_POST['alert_email'] )
                                ? sanitize_text_field( wp_unslash( $_POST['alert_email'] ) )
                                : '';

                            $alert_events = array();

                            if ( isset( $_POST['alert_events'] ) && is_array( $_POST['alert_events'] ) )
                                {
                                    foreach ( $_POST['alert_events'] as $event )
                                        {
                                            if ( is_scalar( $event ) )
                                                $alert_events[] = sanitize_key( wp_unslash( (string) $event ) );
                                        }
                                }

                            $this->logger->save_settings( array(
                                'retention_days' => $retention_days,
                                'alert_email'    => $alert_email,
                                'alert_events'   => $alert_events
                            ) );
     
                            $this->logger->log( 'settings_changed', 'info', array( 'details' => array( 'screen' => 'wp-hide-logs', 'component' => 'retention_alerts' ) ) );
                            wp_safe_redirect( admin_url( 'admin.php?page=wp-hide-logs&updated=1' ) );
                            exit;
                        }

                    if ( isset( $_POST['wph_logs_clear'] ) )
                        {
                            check_admin_referer( 'wph_logs_clear' );
                            global $wpdb;
                            $wpdb->query( "TRUNCATE TABLE {$this->logger->table_name}" );
                            wp_safe_redirect( admin_url( 'admin.php?page=wp-hide-logs&cleared=1' ) );
                            exit;
                        }

                    if ( isset( $_GET['wph_logs_export'] ) )
                        {
                            check_admin_referer( 'wph_logs_export' );
                            $this->export_csv();
                        }
                }

            function get_filters()
                {
                    $events     = $this->logger->get_event_labels();
                    $event      = isset( $_GET['event'] ) ? sanitize_key( wp_unslash( $_GET['event'] ) ) : '';
                    
                    return array( 'event' => isset( $events[ $event ] ) ? $event : '' );
                }

            function get_rows( $limit = 25, $offset = 0 )
                {
                    global $wpdb;
                    $filters = $this->get_filters();
                    $where = '1=1';
                    $args = array();
                    if ( $filters['event'] )
                        {
                            $where .= ' AND event_type = %s';
                            $args[] = $filters['event'];
                        }
                    $args[] = $limit;
                    $args[] = $offset;
                    return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->logger->table_name} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d", $args ) );
                }

            function get_count()
                {
                    global $wpdb;
                    $filters = $this->get_filters();
                    if ( $filters['event'] )
                        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->logger->table_name} WHERE event_type = %s", $filters['event'] ) );
                    return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$this->logger->table_name}" );
                }

            function get_event_count( $event )
                {
                    global $wpdb;
                    return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->logger->table_name} WHERE event_type = %s", $event ) );
                }

            function export_csv()
                {
                    nocache_headers();
                    header( 'Content-Type: text/csv; charset=utf-8' );
                    header( 'Content-Disposition: attachment; filename=wp-hide-activity-log-' . gmdate( 'Y-m-d' ) . '.csv' );
                    $output = fopen( 'php://output', 'w' );
                    fputcsv( $output, array( 'Date (UTC)', 'Event', 'Severity', 'Username', 'User ID', 'IP address', 'User agent', 'Request URI', 'Details' ) );
                    
                    
                    $batch_size = 500;
                    $offset     = 0;
                    $exported   = 0;
                    $max_rows   = 10000;

                    while ( $exported < $max_rows )
                        {
                            $limit = min( $batch_size, $max_rows - $exported );

                            $rows = $this->get_rows( $limit, $offset );

                            if ( empty( $rows ) )
                                break;

                            foreach ( $rows as $row )
                            {
                                fputcsv( $output, array(
                                    $this->csv_safe_value( $row->occurred_at ),
                                    $this->csv_safe_value( $row->event_type ),
                                    $this->csv_safe_value( $row->severity ),
                                    $this->csv_safe_value( $row->username ),
                                    $row->user_id,
                                    $this->csv_safe_value( $row->ip_address ),
                                    $this->csv_safe_value( $row->user_agent ),
                                    $this->csv_safe_value( $row->request_uri ),
                                    $this->csv_safe_value( $row->details )
                                ) );

                                $exported++;
                            }

                            $offset += count( $rows );
                        }
                    
                    fclose( $output );
                    exit;
                }
                
            function csv_safe_value( $value )
                {
                    $value = (string) $value;

                    if ( preg_match( '/^[=+\-@]/', $value ) )
                        $value = "'" . $value;

                    return $value;
                }

            function _render()
                {
                    $settings = $this->logger->get_settings();
                    $events = $this->logger->get_event_labels();
                    $page = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
                    $per_page = 25;
                    $count = $this->get_count();
                    $rows = $this->get_rows( $per_page, ( $page - 1 ) * $per_page );
                    $pages = max( 1, ceil( $count / $per_page ) );
                    $filter = $this->get_filters();
                    $export_url = wp_nonce_url( add_query_arg( array( 'page' => 'wp-hide-logs', 'event' => $filter['event'], 'wph_logs_export' => 1 ), admin_url( 'admin.php' ) ), 'wph_logs_export' );
                    
                    $allow_tags =   WPH_functions::get_general_description_allowed_tags();
                    
                    ?>
                    <div id="wph" class="wrap">
                        <h1><?php esc_html_e( 'WP Hide & Security Enhancer - Logs', 'wp-hide-security-enhancer' ); ?></h1>
                        <?php
                            
                            echo wp_kses ( $this->functions->get_ad_banner(), $allow_tags );
                            
                            $results    =   $this->functions->check_server_environment();
                            $this->functions->output_server_environment_issues( $results );
                            
                        ?>
                        
                        <div id="wph-notices" class="no-wrap"></div>
                        <div id="security-scan" class="general-scan wph-activity-log min-h-screen bg-background">
                            <header><div class="container"><div class="items"><div><h1><span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Threat & Activity Logs', 'wp-hide-security-enhancer' ); ?></h1><p><?php esc_html_e( 'Security-relevant WP Hide events, stored locally on this site.', 'wp-hide-security-enhancer' ); ?></p></div></div><div class="items"><span class="dashicons dashicons-database"></span><span><?php printf( esc_html__( '%d events retained', 'wp-hide-security-enhancer' ), $count ); ?></span></div></div></header>
                            <main class="container">
                                <?php if ( isset( $_GET['updated'] ) ) : ?><div class="notice notice-success inline"><p><?php esc_html_e( 'Log settings saved.', 'wp-hide-security-enhancer' ); ?></p></div><?php endif; ?>
                                <?php if ( isset( $_GET['cleared'] ) ) : ?><div class="notice notice-success inline"><p><?php esc_html_e( 'All log entries were cleared.', 'wp-hide-security-enhancer' ); ?></p></div><?php endif; ?>
                                <div id="scan_overview" class="wph-log-overview">
                                    <?php $this->summary_card( 'login_failed', 'dashicons-warning', __( 'Failed logins', 'wp-hide-security-enhancer' ) ); ?>
                                    <?php $this->summary_card( 'two_factor_failed', 'dashicons-shield-alt', __( '2FA failures', 'wp-hide-security-enhancer' ) ); ?>
                                    <?php $this->summary_card( 'blocked_request', 'dashicons-dismiss', __( 'Blocked requests', 'wp-hide-security-enhancer' ) ); ?>
                                </div>
                                <div id="all-scann-items" class="wph-log-section">
                                    <div class="gc_top"><h2 class="info"><?php esc_html_e( 'Event history', 'wp-hide-security-enhancer' ); ?></h2></div>
                                    <div class="gc"><div class="gc-body wph-log-filter"><form method="get"><input type="hidden" name="page" value="wp-hide-logs" /><label for="wph-log-event"><?php esc_html_e( 'Event type', 'wp-hide-security-enhancer' ); ?></label><select id="wph-log-event" name="event"><option value=""><?php esc_html_e( 'All events', 'wp-hide-security-enhancer' ); ?></option><?php foreach ( $events as $event_id => $label ) : ?><option value="<?php echo esc_attr( $event_id ); ?>" <?php selected( $filter['event'], $event_id ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select><button class="button"><?php esc_html_e( 'Filter', 'wp-hide-security-enhancer' ); ?></button></form><div class="wph-log-actions"><a class="button button-primary" href="<?php echo esc_url( $export_url ); ?>"><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Export CSV', 'wp-hide-security-enhancer' ); ?></a><form method="post" class="wph-log-clear" onsubmit="return confirm('<?php echo esc_js( __( 'Clear all WP Hide activity log entries? This cannot be undone.', 'wp-hide-security-enhancer' ) ); ?>');"><?php wp_nonce_field( 'wph_logs_clear' ); ?><input type="hidden" name="wph_logs_clear" value="1" /><button class="button button-secondary"><?php esc_html_e( 'Clear all logs', 'wp-hide-security-enhancer' ); ?></button></form></div></div></div>
                                    <div class="gc wph-log-table-card"><div class="gc-body"><div class="wph-log-table-wrap"><table class="widefat striped"><thead><tr><th><?php esc_html_e( 'Time', 'wp-hide-security-enhancer' ); ?></th><th><?php esc_html_e( 'Event', 'wp-hide-security-enhancer' ); ?></th><th><?php esc_html_e( 'User / IP', 'wp-hide-security-enhancer' ); ?></th><th><?php esc_html_e( 'Request', 'wp-hide-security-enhancer' ); ?></th><th><?php esc_html_e( 'Details', 'wp-hide-security-enhancer' ); ?></th></tr></thead><tbody><?php if ( empty( $rows ) ) : ?><tr><td colspan="5"><?php esc_html_e( 'No security events match this filter yet.', 'wp-hide-security-enhancer' ); ?></td></tr><?php else : foreach ( $rows as $row ) : $details = json_decode( $row->details, TRUE ); ?><tr><td><strong><?php echo esc_html( get_date_from_gmt( $row->occurred_at, 'Y-m-d H:i:s' ) ); ?></strong><br /><span class="wph-log-severity severity-<?php echo esc_attr( $row->severity ); ?>"><?php echo esc_html( ucfirst( $row->severity ) ); ?></span></td><td><?php echo esc_html( isset( $events[ $row->event_type ] ) ? $events[ $row->event_type ] : $row->event_type ); ?></td><td><?php echo esc_html( $row->username ? $row->username : '—' ); ?><br /><code><?php echo esc_html( $row->ip_address ? $row->ip_address : '—' ); ?></code><span class="wph-log-user-agent"><?php echo esc_html( $row->user_agent ); ?></span></td><td><code><?php echo esc_html( $row->request_uri ? $row->request_uri : '—' ); ?></code></td><td><?php echo esc_html( is_array( $details ) ? implode( '; ', array_map( function( $key, $value ) { return $key . ': ' . ( is_scalar( $value ) ? $value : wp_json_encode( $value ) ); }, array_keys( $details ), $details ) ) : $row->details ); ?></td></tr><?php endforeach; endif; ?></tbody></table></div><?php if ( $pages > 1 ) : ?><div class="tablenav"><div class="tablenav-pages"><?php echo wp_kses_post( paginate_links( array( 'base' => add_query_arg( 'paged', '%#%' ), 'format' => '', 'current' => $page, 'total' => $pages ) ) ); ?></div></div><?php endif; ?></div></div>
                                </div>
                                <div class="gc wph-log-settings">
                                    <div class="gc-header">
                                        <div class="item">
                                            <h3><?php esc_html_e( 'Retention & alerts', 'wp-hide-security-enhancer' ); ?></h3>
                                            <p><?php esc_html_e( 'Email notifications are throttled to one per event type and IP address every five minutes.', 'wp-hide-security-enhancer' ); ?></p>
                                        </div>
                                    </div>
                                    <div class="gc-body">
                                        <form method="post"><?php wp_nonce_field( 'wph_logs_settings' ); ?>
                                            <input type="hidden" name="wph_logs_settings" value="1" />
                                            <p class="wph-log-setting-row">
                                                <label for="wph-log-retention">
                                                    <strong><?php esc_html_e( 'Retention (days)', 'wp-hide-security-enhancer' ); ?></strong>
                                                </label>
                                                <input id="wph-log-retention" type="number" min="1" max="3650" name="retention_days" value="<?php echo esc_attr( $settings['retention_days'] ); ?>" />
                                            </p>
                                            <p class="wph-log-setting-row"><label for="wph-log-email"><strong><?php esc_html_e( 'Alert email recipients', 'wp-hide-security-enhancer' ); ?></strong></label><input id="wph-log-email" class="regular-text" type="text" name="alert_email" value="<?php echo esc_attr( $settings['alert_email'] ); ?>" /><span class="description"><?php esc_html_e( 'Use commas for multiple addresses.', 'wp-hide-security-enhancer' ); ?></span></p>
                                            <p><strong><?php esc_html_e( 'Send alerts for', 'wp-hide-security-enhancer' ); ?></strong><br /><?php foreach ( $events as $event_id => $label ) : ?><label class="wph-log-checkbox"><input type="checkbox" name="alert_events[]" value="<?php echo esc_attr( $event_id ); ?>" <?php checked( in_array( $event_id, $settings['alert_events'], TRUE ) ); ?> /> <?php echo esc_html( $label ); ?></label><?php endforeach; ?></p>
                                            <p class="wph-log-save"><button class="button button-primary"><?php esc_html_e( 'Save log settings', 'wp-hide-security-enhancer' ); ?></button></p>
                                        </form>
                                    </div>
                                </div>
                            </main>
                        </div>
                    </div>
                    <?php
                }

            function summary_card( $event, $icon, $label )
                {
                    ?><div class="score-item"><div class="icon <?php echo esc_attr( $event === 'login_failed' || $event === 'two_factor_failed' ? 'failed' : '' ); ?>"><span class="dashicons <?php echo esc_attr( $icon ); ?>"></span></div><div><span><?php echo esc_html( $this->get_event_count( $event ) ); ?></span><p><?php echo esc_html( $label ); ?></p></div></div><?php
                }
        }

?>

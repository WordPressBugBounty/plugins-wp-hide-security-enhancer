<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_login_captcha_google_v2
        {
            var $captcha_id;
            var $wph;
            
            function __construct()
                {
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    $this->captcha_id   =   'google_v2';
                }
                
            function get_captcha_options()
                {
                 
                    $captcha_options    =   array ( 
                                                    
                                                            $this->captcha_id  =>  array (
                                                                                                'g2-site-key'           =>  array ( 
                                                                                                                                        'title' =>  __('Site Key',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'input'
                                                                                                                                        ),
                                                                                                'g2-site-secret-key'    =>  array ( 
                                                                                                                                        'title' =>  __('Secret Key',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'input'
                                                                                                                                        ),
                                                                                                'g2-theme'              =>  array ( 
                                                                                                                                        'title' =>  __('Theme',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'select',
                                                                                                                                        'options'   =>  array ( 
                                                                                                                                                                'light' =>  __('Light',     'wp-hide-security-enhancer'),
                                                                                                                                                                'dark'  =>  __('Dark',     'wp-hide-security-enhancer'),
                                                                                                                                                                )
                                                                                                                                        ),
                                                                                                'g2-language'              =>  array ( 
                                                                                                                                        'title' =>  __('Language',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'select',
                                                                                                                                        'options'   =>  array ( 
                                                                                                                                                                'auto'  => __( 'Auto Detect', 'wp-hide-security-enhancer' ),
                                                                                                                                                                'ar'    => __( 'Arabic',                'wp-hide-security-enhancer' ),
                                                                                                                                                                'de'    => __( 'German',                'wp-hide-security-enhancer' ),
                                                                                                                                                                'en'    => __( 'English',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'es'    => __( 'Spanish',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'fa'    => __( 'Farsi/Persian',         'wp-hide-security-enhancer' ),
                                                                                                                                                                'fr'    => __( 'French',                'wp-hide-security-enhancer' ),
                                                                                                                                                                'id'    => __( 'Indonesian',            'wp-hide-security-enhancer' ),
                                                                                                                                                                'it'    => __( 'Italian',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'ja'    => __( 'Japanese',              'wp-hide-security-enhancer' ),
                                                                                                                                                                'ko'    => __( 'Korean',                'wp-hide-security-enhancer' ),
                                                                                                                                                                'nl'    => __( 'Dutch',                 'wp-hide-security-enhancer' ),
                                                                                                                                                                'pl'    => __( 'Polish',                'wp-hide-security-enhancer' ),
                                                                                                                                                                'pt'    => __( 'Portuguese',            'wp-hide-security-enhancer' ),
                                                                                                                                                                'pt-BR' => __( 'Portuguese (Brazil)',   'wp-hide-security-enhancer' ),
                                                                                                                                                                'ro'    => __( 'Romania',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'ru'    => __( 'Russian',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'tr'    => __( 'Turkish',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'uk'    => __( 'Ukrainian',             'wp-hide-security-enhancer' ),
                                                                                                                                                                'zh'    => __( 'Chinese',               'wp-hide-security-enhancer' ),
                                                                                                                                                                'zh-CN' => __( 'Chinese (Simplified)',  'wp-hide-security-enhancer' ),
                                                                                                                                                                'zh-TW' => __( 'Chinese (Traditional)', 'wp-hide-security-enhancer' )
                                                                                                                                                                )
                                                                                                                                        ),
                                                                                                )
                                                    );
                    return $captcha_options;   
                    
                    
                }
            
            function get_options( $current_settings =   array() )
                {
                    $html_options    =   $this->get_captcha_options();
                    $html_options    =   $html_options[ $this->captcha_id ];
                    
                    if ( count ( $current_settings ) < 1 )
                        $current_settings =   (array)$this->wph->functions->get_module_item_setting( 'captcha_type' );
                    
                    foreach ( $html_options as  $option_key =>  $data )
                        {
                            if ( ! isset( $current_settings[ $option_key ] ) )
                                {
                                    switch ( $data['type'] )
                                        {
                                            case 'input' :   
                                                            $current_settings[ $option_key ]  =   '';
                                                            break;
                                            case 'select' :   
                                                            reset ( $data['options'] );
                                                            $current_settings[ $option_key ]  =   key ( $data['options'] );
                                                            break;
                                        }
                                }
                        }
                        
                    return $current_settings;                    
                }
                
            
            function get_module_description( $values )
                {
                    $html   =   '';
                    
                    if ( ! isset ( $values['g2_checked_for'] ) )
                        $values['g2_checked_for'] =   '';
                    if ( ! empty ( $values['g2-site-key'] )  &&  ! empty ( $values['g2-site-secret-key'] )   &&  md5 ( $values['g2-site-key'] . $values['g2-site-secret-key'] )  !=  $values['g2_checked_for'] )
                        {
                            ob_start();
                                                                
                            $interface_errors   =   get_transient( 'wph-process_API_interface_errors');
                            delete_transient ( 'wph-process_API_interface_errors' );
                            
                            if ( ! empty ( $interface_errors ) )
                                echo '<p class="important">' . esc_html ( $interface_errors ) . '</p>';
                            
                            ?><div id="captch_test" class="captcha-integration <?php echo esc_attr ( $this->captcha_id ) ?>">
                            <div><?php $this->html_field() ?></div>

                            <p></p>
                            <button class="button-primary red" onclick="WPH.captcha_test(); return false;"><?php esc_html_e('Test API',  'wp-hide-security-enhancer') ?></button>
                            <p><?php esc_html_e('The captcha will not show on the front side until the Test is successful.',  'wp-hide-security-enhancer') ?></p>
                            <input type="hidden" id="api_test" name="api_test" value="" />
                            
                            </div>
                            <?php
                            
                            $html   =   ob_get_clean();
                        }
                        
                    if ( ! empty ( $values['g2-site-key'] )  &&  ! empty ( $values['g2-site-secret-key'] )   &&  md5 ( $values['g2-site-key'] . $values['g2-site-secret-key'] )  ==  $values['g2_checked_for'] )
                        {
                            ob_start();
                                   
                            ?><div id="captch_test" class="captcha-integration <?php echo esc_attr ( $this->captcha_id ) ?>">
                            <p class="green"><?php esc_html_e('The Google Captcha V2 integration is completed.',  'wp-hide-security-enhancer') ?></p>
                            
                            <?php 
                                
                            $this->html_field();
                            
                            ?></div><?php
                            
                            $html   =   ob_get_clean();
                        }
                        
                    return $html;
                
                }
                
            
            function get_module_help()
                {
                    ?><p><?php esc_html_e('You can get your site key and secret key from',  'wp-hide-security-enhancer') ?> <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a></p><?php
                    ?><p><?php esc_html_e('After filling in and saving the options, remember to click the <b class="important">Test API</b> button located at the top of this section. The CAPTCHA won\'t appear on the front end until the test is successfully completed',  'wp-hide-security-enhancer') ?></p><?php
                }
                
                
            function api_test( $module_settings )
                {
                    if ( isset ( $_POST['g-recaptcha-response'] ) )
                        {
                            
                            $api_response   =   $this->g2_api_check( $_POST['g-recaptcha-response'] );
        
                            if( $api_response->success )
                                {
                                    $settings_hash  =   md5( $module_settings['g2-site-key'] . $module_settings['g2-site-secret-key'] );
                                    $module_settings['g2_checked_for']    =   $settings_hash;   
                                }
                                else
                                {
                                    $CaptchaAPIResponse =   '';
                                    foreach ( $api_response->{'error-codes'}    as  $error_code )
                                        {
                                            $CaptchaAPIResponse   .=  $error_code .   ' ';
                                        }
                                                                                
                                    set_transient( 'wph-process_API_interface_errors', __('The API returned an error', 'wp-hide-security-enhancer') . ': ' . $CaptchaAPIResponse, HOUR_IN_SECONDS );    
                                    
                                }
                        }

                    return $module_settings;
                }
                
            
            function init_captcha( $saved_field_data ) 
                {
                    if ( $saved_field_data['captcha_type'] == 'google_v2'
                        && ! empty( $saved_field_data['g2-site-key'] )
                        && ! empty( $saved_field_data['g2-site-secret-key'] )
                        && isset( $saved_field_data['g2_checked_for'] )
                        && ! empty( $saved_field_data['g2_checked_for'] )
                        && md5( $saved_field_data['g2-site-key'] . $saved_field_data['g2-site-secret-key'] ) == $saved_field_data['g2_checked_for'] )
                        {
                            add_action( 'plugins_loaded', array( $this, 'captcha_hooks' ) );
                        }
                }
                
                
            function captcha_hooks()
                {
                    add_action( 'login_form',           array( $this, 'login_form' ) );
                    add_action( 'authenticate',         array( $this, 'authenticate' ), 99 );

                    add_action( 'lostpassword_form',    array( $this, 'lostpassword_form' ) );
                    add_action( 'lostpassword_post',    array( $this, 'lostpassword_post' ), 99 );

                    add_action( 'register_form',        array( $this, 'register_form' ) );
                    add_action( 'registration_errors',  array( $this, 'registration_errors' ), 99 );

                    if ( class_exists( 'WooCommerce' ) ) {
                        add_action( 'woocommerce_login_form',                       array( $this, 'login_form' ) );
                        add_filter( 'woocommerce_process_login_errors',             array( $this, 'wc_authenticate' ), 99, 3 );

                        add_action( 'woocommerce_lostpassword_form',                array( $this, 'lostpassword_form' ) );
                        add_filter( 'woocommerce_process_lost_password_errors',     array( $this, 'wc_lostpassword_post' ), 99, 2 );

                        add_action( 'woocommerce_register_form',                    array( $this, 'register_form' ) );
                        add_filter( 'woocommerce_process_registration_errors',      array( $this, 'wc_registration_errors' ), 99, 4 );
                    }


                    if ( class_exists( 'bbPress' ) ) {
                        add_action( 'bbp_login_form',           array( $this, 'login_form' ) );
                        add_filter( 'bbp_authenticate',         array( $this, 'authenticate' ), 99 );

                        add_action( 'bbp_register_form',        array( $this, 'register_form' ) );
                        add_filter( 'bbp_registration_errors',  array( $this, 'registration_errors' ), 99 );
                    }

                    if ( class_exists( 'BuddyPress' ) ) {
                        add_action( 'bp_before_login_form_fields',      array( $this, 'login_form' ) );
                        add_action( 'bp_signup_validate',               array( $this, 'bp_registration_errors' ), 99 );
                        add_action( 'bp_before_registration_submit_buttons', array( $this, 'register_form' ) );
                    }

                    if ( class_exists( 'UM' ) ) {
                        add_action( 'um_after_login_fields',        array( $this, 'login_form' ) );
                        add_filter( 'um_submit_form_errors_hook__login', array( $this, 'um_authenticate' ), 99, 2 );

                        add_action( 'um_after_register_fields',     array( $this, 'register_form' ) );
                        add_filter( 'um_submit_form_errors_hook__register', array( $this, 'um_registration_errors' ), 99, 2 );
                    }   
                }
                
                
            
            function html_field()
                {
                    
                    $values =   $this->get_options();   
                    
                    $rand   =   rand ( 1, 99999 );
                    
                    ?>
                        <style>
                            #login {width: 350px}
                            div.g-recaptcha {padding-bottom: 20px}
                        </style>
                        <script src="https://www.google.com/recaptcha/api.js?hl=<?php 
                        
                        if ( $values['g2-language'] ==  'auto' )
                            echo get_locale();
                            else
                            echo $values['g2-language']; ?>" async defer></script>
                            
                        <div class="g-recaptcha" data-sitekey="<?php echo $values['g2-site-key'] ?>" data-theme="<?php echo $values['g2-theme'] ?>"></div>    
                        
                    
                    <?php
                    
                }
                
            function g2_api_check( $postdata )
                {
                    $module_settings =   $this->wph->functions->get_module_item_setting( 'captcha_type' );
      
                    $verify                 =   wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . $module_settings['g2-site-secret-key'] . '&response=' . $postdata );
                    $verify                 =   wp_remote_retrieve_body( $verify );
                    
                    $response               =   json_decode ( $verify );
                    
                    return $response;

                }
                
                
            function login_form()
                {
                    $this->html_field();
                }
            
            
            function authenticate( $user )
                {
                    if ( ! is_object ( $user )  ||  ! isset ( $user->ID ) ) 
                        return $user;
                        
                    // Let WooCommerce handle its own login form via woocommerce_process_login_errors
                    if ( isset( $_POST['woocommerce-login-nonce'] ) )
                        return $user;
                    
                    if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST )  ||  ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
                        return $user;

                    if ( is_wp_error ( $user ) )
                        return $user;

                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) )
                        return new WP_Error( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        
                    $api_response   =   $this->g2_api_check( $_POST['g-recaptcha-response'] );
                        
                    if( $api_response->success )
                        return $user;
           
                    $user = new WP_Error( 'g2_error', esc_html__('Unable to verify that you are human.', 'wp-hide-security-enhancer') );
                                                    
                    return $user;
                
                }
                
            function wc_authenticate( $validation_errors, $username, $password ) 
                {
                    if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
                        return $validation_errors;

                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $validation_errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $validation_errors;
                    }

                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $validation_errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );

                    return $validation_errors;
                }
                
                
            function register_form()
                {
                    $this->html_field();
                } 
                
            
            function registration_errors( $errors )
                {

                    if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
                        return $errors;

                    // ADD THIS GUARD - currently missing
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $errors->add( 'g2_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );
                        return $errors;
                    }

                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g2_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );

                    return $errors;
                                        
                }
                
                
            function lostpassword_form()
                {
                    $this->html_field();
                } 
                
            
            function lostpassword_post( $errors )
                {
                    if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
                        return $errors;

                    // ADD THIS GUARD - currently missing
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $errors->add( 'g2_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );
                        return $errors;
                    }

                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g2_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );

                    return $errors;
                                        
                }
                
                
            /**
            * 
            * WooCommerce lost password
            * @param mixed $errors
            * @param mixed $user_data
            */
            function wc_lostpassword_post( $errors, $user_data ) 
                {
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $errors;
                    }
                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                    return $errors;
                }

            /**
            * 
            * WooCommerce registration
            * @param mixed $errors
            * @param mixed $username
            * @param mixed $password
            * @param mixed $email
            */
            function wc_registration_errors( $errors, $username, $password, $email ) 
                {
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $errors;
                    }
                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g2_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                    return $errors;
                }

            /**
            * 
            * BuddyPress registration validation
            */
            function bp_registration_errors() 
                {
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        bp_core_add_message( __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ), 'error' );
                        return;
                    }
                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        bp_core_add_message( __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ), 'error' );
                }

            /**
            * 
            * Ultimate Member login
            * @param mixed $args
            * @param mixed $form_data
            * @return mixed
            */
            function um_authenticate( $args, $form_data ) 
                {
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        UM()->form()->add_error( 'g2_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return;
                    }
                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        UM()->form()->add_error( 'g2_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                }

            /**
            * 
            * Ultimate Member registration
            * @param mixed $args
            * @param mixed $form_data
            * @return mixed
            */
            function um_registration_errors( $args, $form_data ) 
                {
                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        UM()->form()->add_error( 'g2_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return;
                    }
                    $api_response = $this->g2_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        UM()->form()->add_error( 'g2_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                }
            
            
        }
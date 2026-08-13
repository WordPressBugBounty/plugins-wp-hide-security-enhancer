<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_login_captcha_google_v3
        {
            var $captcha_id;
            var $wph;
            
            function __construct()
                {
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    $this->captcha_id   =   'google_v3';
                }
                
            function get_captcha_options()
                {
                 
                    $captcha_options    =   array ( 
                                                    
                                                            $this->captcha_id  =>  array (
                                                                                                'g3-site-key'           =>  array ( 
                                                                                                                                        'title' =>  __('Site Key',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'input'
                                                                                                                                        ),
                                                                                                'g3-site-secret-key'    =>  array ( 
                                                                                                                                        'title' =>  __('Secret Key',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'  =>  'input'
                                                                                                                                        ),
                                                                                                'g3-required-score'    =>  array ( 
                                                                                                                                        'title'     =>  __('Required Score to validate ( range 0.0 - 1.0 ). Default 0.5 ',     'wp-hide-security-enhancer'),
                                                                                                                                        'type'      =>  'input',
                                                                                                                                        'default'   =>  '0.5',
                                                                                                                                        'validation'    =>  array ( $this, 'field_validation')
                                                                                                                                        )
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
                    
                    if ( ! isset ( $values['g3_checked_for'] ) )
                        $values['g3_checked_for'] =   '';
                    if ( ! empty ( $values['g3-site-key'] )  &&  ! empty ( $values['g3-site-secret-key'] )   &&  md5 ( $values['g3-site-key'] . $values['g3-site-secret-key'] )  !=  $values['g3_checked_for'] )
                        {
                            ob_start();
                                                                
                            $interface_errors   =   get_transient( 'wph-process_API_interface_errors');
                            delete_transient ( 'wph-process_API_interface_errors' );
                            
                            if ( ! empty ( $interface_errors ) )
                                echo '<p class="important">' . esc_html ( $interface_errors ) . '</p>';
                            
                            ?><div id="captch_test" class="captcha-integration <?php echo esc_attr (  $this->captcha_id ) ?>">
                            <script src="https://www.google.com/recaptcha/api.js"></script>
                            <script>
                               function onSubmit(token) {
                                   jQuery( '#api_test' ).val( 'true' )
                                 document.getElementById("wph-form").submit();
                               }
                               
                               window.onload = function() {
                                    var iframe = document.querySelector('iframe[title="reCAPTCHA"]');
                                    var elmnt = iframe.contentWindow.document.getElementsByTagName("H1")[0];
                                }
                               
                               
                             </script>
                            <button class="g-recaptcha button-primary red" data-sitekey="<?php echo esc_attr ( $values['g3-site-key'] ) ?>" data-callback='onSubmit' data-action='submit'><?php esc_html_e('Test API',  'wp-hide-security-enhancer') ?></button>
                            <p><?php esc_html_e('If the Test API button is not triggering, check the Google badge message on the bottom-right corner of your screen. The provided site key may be invalid for this domain.',  'wp-hide-security-enhancer') ?></p>
                            <p><?php esc_html_e('The captcha will not show on the front side until the Test is successful.',  'wp-hide-security-enhancer') ?></p>
                            <input type="hidden" id="api_test" name="api_test" value="" />
                            
                            </div>
                            <?php
                            
                            $html   =   ob_get_clean();
                        }
                        
                    if ( ! empty ( $values['g3-site-key'] )  &&  ! empty ( $values['g3-site-secret-key'] )   &&  md5 ( $values['g3-site-key'] . $values['g3-site-secret-key'] )  ==  $values['g3_checked_for'] )
                        {
                            ob_start();
                                   
                            ?><div id="captch_test" class="captcha-integration <?php echo $this->captcha_id ?>">
                            <p class="green"><?php esc_html_e('The Google Captcha V3 integration is completed.',  'wp-hide-security-enhancer') ?></p>
                            
                            <?php 
                                
                            $this->html_field();
                            
                            ?></div><?php
                            
                            $html   =   ob_get_clean();
                        }
                        
                    return $html;
                
                }
                
            
            function get_module_help()
                {
                    ?><p><?php esc_html_e('You can get your site key and secret key from',  'wp-hide-security-enhancer') ?> <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a></p>
                    <p><?php esc_html_e('After filling in and saving the options, remember to click the <b class="important">Test API</b> button located at the top of this section. The CAPTCHA won\'t appear on the front end until the test is successfully completed',  'wp-hide-security-enhancer') ?></p>
                    <p>&nbsp;</p><h4>The Required Score</h4><p><?php esc_html_e('reCAPTCHA v3 returns a score for each request without user friction. The score is based on interactions with your site and enables you to take an appropriate action for your site.',  'wp-hide-security-enhancer') ?></p>
                    <p><?php esc_html_e('The default score is 0.5 which is considered a safe value to distinguish between bots and real users. ',  'wp-hide-security-enhancer') ?></p>
                    <p><b class="important"><?php esc_html_e('Warning',  'wp-hide-security-enhancer') ?></b></p>
                    <p><?php esc_html_e('Setting an excessively high value may result in being locked out of your site\'s login. Conversely, using an extremely low value can validate bots. The optimal value is 0.5.',  'wp-hide-security-enhancer') ?></p><?php
                    
                }
                
                
            function api_test( $module_settings )
                {
                    if ( isset ( $_POST['g-recaptcha-response'] ) )
                        {
                            
                            $api_response   =   $this->g3_api_check( $_POST['g-recaptcha-response'] );
        
                            if( $api_response->success )
                                {
                                    $settings_hash  =   md5( $module_settings['g3-site-key'] . $module_settings['g3-site-secret-key'] );
                                    $module_settings['g3_checked_for']    =   $settings_hash;   
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
                    if ( $saved_field_data['captcha_type'] == 'google_v3'
                        && ! empty( $saved_field_data['g3-site-key'] )
                        && ! empty( $saved_field_data['g3-site-secret-key'] )
                        && isset( $saved_field_data['g3_checked_for'] )
                        && ! empty( $saved_field_data['g3_checked_for'] )
                        && md5( $saved_field_data['g3-site-key'] . $saved_field_data['g3-site-secret-key'] ) == $saved_field_data['g3_checked_for'] )
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
                        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $values['g3-site-key'] ?>" async defer></script>
                            
                        <script>
                            function reCaptchaSubmit(e) {
                                e.preventDefault();
                                
                                var container = this;
                                
                                // Capture any submit button value that triggered this form before losing it
                                var submitBtn = container.querySelector('button[type="submit"][name], input[type="submit"][name]');
                                
                                grecaptcha.ready(function() {
                                    grecaptcha.execute('<?php echo $values['g3-site-key'] ?>', {action: 'submit'}).then(function(token) {
                                        var input_field         =   document.createElement("input");
                                        input_field.type        =   "hidden";
                                        input_field.name        =   "g-recaptcha-response" ;
                                        input_field.className   =   'recaptcha-response'
                                        input_field.value       = token ;
                                        container.appendChild( input_field );
                                        
                                        // Re-inject the submit button value lost due to programmatic submit
                                        if ( submitBtn && submitBtn.name ) {
                                            var btn_field       =   document.createElement("input");
                                            btn_field.type      =   "hidden";
                                            btn_field.name      =   submitBtn.name;
                                            btn_field.value     =   submitBtn.value;
                                            container.appendChild( btn_field );
                                        }

                                        container.submit();
                                    });
                                });
                            }

                            window.onload = function() {
                                const forms = document.querySelectorAll('form');       
                                Array.from( forms ).forEach(( item ) => {
                                  item.addEventListener("submit", reCaptchaSubmit);
                                });
                            }
                                           
                        </script>   
                        
                    
                    <?php
                    
                }
                
            function g3_api_check( $postdata )
                {
                    $module_settings        =   $this->wph->functions->get_module_item_setting( 'captcha_type' );
      
                    $verify                 =   wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . $module_settings['g3-site-secret-key'] . '&response=' . $postdata );
                    $verify                 =   wp_remote_retrieve_body( $verify );
                    
                    $response               =   json_decode ( $verify );
                    
                    //check the score
                    $g3_required_score     =   isset ( $module_settings['g3-required-score'] )  ?  (float)$module_settings['g3-required-score']   :   0.5;
                    if ( $response->score   <   $g3_required_score )
                        $response->success  =   FALSE;
                    
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
                        return new WP_Error( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        
                    $api_response   =   $this->g3_api_check( $_POST['g-recaptcha-response'] );
                        
                    if( $api_response->success )
                        return $user;
           
                    $user = new WP_Error( 'g3_error', esc_html__('Unable to verify that you are human.', 'wp-hide-security-enhancer') );
                                                    
                    return $user;
                
                }
                
            function wc_authenticate( $validation_errors, $username, $password ) 
                {
                    if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) )
                        return $validation_errors;

                    if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
                        $validation_errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $validation_errors;
                    }

                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $validation_errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );

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
                        $errors->add( 'g3_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );
                        return $errors;
                    }

                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g3_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );

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
                        $errors->add( 'g3_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );
                        return $errors;
                    }

                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );

                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g3_error', sprintf( '<strong>%s</strong>: %s', __( 'Error!', 'wp-hide-security-enhancer' ), __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) ) );

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
                        $errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $errors;
                    }
                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
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
                        $errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return $errors;
                    }
                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        $errors->add( 'g3_error', esc_html__( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
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
                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );
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
                        UM()->form()->add_error( 'g3_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return;
                    }
                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        UM()->form()->add_error( 'g3_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
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
                        UM()->form()->add_error( 'g3_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                        return;
                    }
                    $api_response = $this->g3_api_check( $_POST['g-recaptcha-response'] );
                    if ( $api_response->success !== TRUE )
                        UM()->form()->add_error( 'g3_error', __( 'Unable to verify that you are human.', 'wp-hide-security-enhancer' ) );
                }
                
                
            function field_validation( $data )
                {
                    extract ( $data );
                    
                    switch ( $field_key )
                        {
                            case 'g3-required-score'    :
                                                            $value  =   (float)$value;
                                                            if ( $value < 0 ||  $value > 1 )
                                                                $value = 0.5;
                                                            break;
                            
                        }
                    
                    return $value;
                }
            
            
        }
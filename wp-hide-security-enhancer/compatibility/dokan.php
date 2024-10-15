<?php


    /**
    * Compatibility     :   Dokan
    * Introduced at     :   3.9.4
    * Last checked on   :   3.11.5
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_dokan
        {
                        
            var $wph;
            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                        
                    add_action ( 'dokan_seller_registration_after_shopurl_field' , array ( $this, 'dokan_seller_registration_after_shopurl_field' ) );

                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'dokan-lite/dokan.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            function dokan_seller_registration_after_shopurl_field()
                {
                    if ( ! did_action( 'dokan_vendor_reg_form_start' ) )
                        return;
                        
                    $this->wph->functions->remove_anonymous_object_filter('register_form',                  'WPH_module_login_captcha_ct', 'register_form' );
                    $this->wph->functions->remove_anonymous_object_filter('registration_errors',            'WPH_module_login_captcha_ct', 'registration_errors' );
                    $this->wph->functions->remove_anonymous_object_filter('register_form',                  'WPH_module_login_captcha_google_v2', 'register_form' );
                    $this->wph->functions->remove_anonymous_object_filter('registration_errors',            'WPH_module_login_captcha_google_v2', 'registration_errors' );
                    $this->wph->functions->remove_anonymous_object_filter('register_form',                  'WPH_module_login_captcha_google_v3', 'register_form' );
                    $this->wph->functions->remove_anonymous_object_filter('registration_errors',            'WPH_module_login_captcha_google_v3', 'registration_errors' );
                    
                }
            
           
        }
        
        
    new WPH_conflict_handle_dokan();


?>
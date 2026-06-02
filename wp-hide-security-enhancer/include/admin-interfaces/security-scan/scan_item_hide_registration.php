<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_registration    extends WPH_security_scan_item
        {
            var $wph;
                     
            function __construct()
                {
                    $this->id       =   $this->get_id();
                   
                    global $wph;
                    
                    $this->wph  =   $wph;
                }   
            
            public function get_id()
                {
                    return 'hide_registration';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('User Registration',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("Through your site, if the WordPress Membership option is active, anyone can register.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $found_issue        =   FALSE;
                    
                    $users_can_register       =   get_option('users_can_register');
                    
                    if ( ! empty ( $users_can_register ) )
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'The Registration should be customised or disabled through Dashboard > Settings.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                            
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The option appears properly configured.', 'wp-hide-security-enhancer' );
                        }
                        
                    
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary wph-pro" target="_blank" href="https://wp-hide.com/pricing/">PRO</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; Through your site, if the WordPress Membership option is active, anyone can register.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
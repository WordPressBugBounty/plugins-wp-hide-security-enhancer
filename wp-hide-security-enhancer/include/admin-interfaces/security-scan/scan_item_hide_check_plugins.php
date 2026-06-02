<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_check_plugins    extends WPH_security_scan_item
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
                    return 'hide_check_plugins';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('New Plugins Path',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("In WordPress, a plugin is a small software application that extends the features and functions of a WordPress website.
                                                                <br />Plugins play a major role in building great websites using WordPress. They make it easier for users to add features to their website without knowing a single line of code.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $found_issue        =   FALSE;
                    $option_value       =   $this->wph->functions->get_module_item_setting('new_plugin_path');
                    
                    if ( empty ( $option_value ) )
                        $found_issue    =   TRUE;
                    
                    $found_within_code  =   FALSE;
                    if ( ! $found_issue &&  $this->wph->security_scan->remote_html )
                        {
                            $seek_url   =   includes_url();
                            $seek_url   =   str_replace( array('https://', 'http://'), "", $seek_url );
                            if ( stripos( $this->wph->security_scan->remote_html, $seek_url ) )
                                $found_within_code    =   TRUE;
                        }
                    
                    if ( $found_within_code )
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            if ( empty ( $option_value ) )
                                {
                                    $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'The default plugins path has not been customised.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                                }
                                else
                                {
                                    $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'The default plugins path is still found within the source HTML.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                                    if ( $found_within_code )
                                        $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'Ensure you cleared the site cache, then check again.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                                }
                            
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The default plugins path cannot be found anymore through the site source.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url ( 'admin.php?page=wp-hide&component=plugins' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; In WordPress, a plugin is a small software application that extends the features and functions of a WordPress website. Plugins play a major role in building great websites using WordPress. They make it easier for users to add features to their website without knowing a single line of code.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
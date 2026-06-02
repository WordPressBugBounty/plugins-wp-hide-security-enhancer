<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_wp_debug    extends WPH_security_scan_item
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
                    return 'wp_debug';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'WP Debug',
                                        'icon'          =>  'dashicons-code-standards',
                                        
                                        'help'          =>  __("WordPress includes built-in debugging features to help developers identify issues and maintain consistent code across core, plugins, and themes. However, on production sites, debugging should be disabled, as it may expose file paths and other sensitive information that could be used to compromise your site. ",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        
                                        'callback'      =>  'scan_item_wp_debug',
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $_JSON_response['info']  =   __( 'Current value: ', 'wp-hide-security-enhancer' ) . ( WP_DEBUG  === TRUE ? 'TRUE' : 'FALSE' );

                    if ( defined ( 'WP_DEBUG' ) &&  WP_DEBUG    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            $_JSON_response['description']  =   __( '', 'wp-hide-security-enhancer' );
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'WP_DEBUG constant is currently enabled. While useful for development, it can expose sensitive information about your site. Check your', 'wp-hide-security-enhancer' ) . ' <code>wp-config.php</code> ' . __( 'file and disable it by setting the constant to', 'wp-hide-security-enhancer' ) . ' <code>false</code> ' . __( 'or commenting out its declaration.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="row labels">
                                                                        <div class="item">
                                                                            <div class="label">' . __( 'Expected', 'wp-hide-security-enhancer' ) .'</div>
                                                                            <div class="value">FALSE</div>
                                                                        </div>

                                                                        <div class="item">
                                                                            <div class="label">' . __( 'Current', 'wp-hide-security-enhancer' ) . '</div>
                                                                            <div class="value">TRUE</div>
                                                                        </div>
                                                                    </div>
                                                                                                                                                
                                                                 </div>';
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The WP_DEBUG is disabled.', 'wp-hide-security-enhancer' );
                        }  
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot;WP_DEBUG constant is currently enabled. While useful for development, it can expose sensitive information about your site. Check your wp-config.php file and disable it by setting the constant to false or commenting out its declaration.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
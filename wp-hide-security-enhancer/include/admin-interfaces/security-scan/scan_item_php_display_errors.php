<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_php_display_errors    extends WPH_security_scan_item
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
                    return 'php_display_errors';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'PHP display_errors',
                                        'icon'          =>  'dashicons-admin-generic',
                                        
                                        'help'          =>  __("The display_error setting in PHP is used to determine whether errors should be printed to the screen or not.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $display_errors = (bool)ini_get( 'display_errors' );
                    
                    if ( $display_errors    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;

                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The display_errors setting is currently enabled. This may expose sensitive information such as file paths, configuration details, or code structure to visitors. For production environments, it is recommended to disable error display to prevent information disclosure.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'To fix this security issue, change the php.ini', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>display_errors = "off"</li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'You can disable this within wp-config.php', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>ini_set("display_errors", "0");</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';
                                                                 
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The display_errors is Off.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; The display_error setting in PHP is used to determine whether errors should be printed to the screen or not.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
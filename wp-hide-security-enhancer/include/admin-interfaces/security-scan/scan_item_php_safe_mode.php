<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_php_safe_mode    extends WPH_security_scan_item
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
                    return 'php_safe_mode';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'PHP safe_mode',
                                        'icon'          =>  'dashicons-admin-generic',
                                        
                                        'help'          =>  __("The PHP safe mode is an attempt to solve the shared-server security problem. It is architecturally incorrect to try to solve this problem at the PHP level, but since the alternatives at the web server and OS levels aren't very realistic, many people, especially ISP's, use safe mode for now.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $safe_mode = (bool)ini_get( 'safe_mode' );

                    if ( $safe_mode    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;

                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The register_globals setting is currently enabled. This outdated and insecure feature can automatically turn user input into global variables, increasing the risk of variable injection and other attacks. It is strongly recommended to disable this setting, as it is deprecated and unsafe for modern applications.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'To fix this security issue, change the php.ini', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>safe_mode = "off"</li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'You can disable this within .htaccess', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>php_flag safe_mode off</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The safe_mode is Off.', 'wp-hide-security-enhancer' );
                        }  
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; The PHP safe mode is an attempt to solve the shared-server security problem. It is architecturally incorrect to try to solve this problem at the PHP level, but since the alternatives at the web server and OS levels aren\'t very realistic, many people, especially ISP\'s, use safe mode for now.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
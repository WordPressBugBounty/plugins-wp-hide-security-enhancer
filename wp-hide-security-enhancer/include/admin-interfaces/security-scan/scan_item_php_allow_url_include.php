<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_php_allow_url_include    extends WPH_security_scan_item
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
                    return 'php_allow_url_include';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'PHP allow_url_include',
                                        'icon'          =>  'dashicons-admin-generic',
                                        
                                        'help'          =>  __("The allow_url_include allows a developer to include a remote file using a URL rather than a local file path. This technique is used to reduce the load on the server. 
                                                                There are many servers with PHP configuration directive allow_url_include as enabled. When this setting is enabled, the server’s directory allows data retrieval from remote locations.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $allow_url_include = (bool)ini_get( 'allow_url_include' );

                    if ( $allow_url_include    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;

                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The allow_url_include setting is currently enabled. This option allows PHP to include files from external URLs, which can significantly increase the risk of remote code execution if exploited. For better security, it is strongly recommended to disable this setting in your server configuration.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'To fix this security issue, change the php.ini', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>allow_url_include = "off"</li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'You can disable this within wp-config.php', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>ini_set("allow_url_include", "0");</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';
                                                                 
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The allow_url_include is Off.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; The allow_url_include allows a developer to include a remote file using a URL rather than a local file path. This technique is used to reduce the load on the server. There are many servers with PHP configuration directive allow_url_include as enabled. When this setting is enabled, the server’s directory allows data retrieval from remote locations. .&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
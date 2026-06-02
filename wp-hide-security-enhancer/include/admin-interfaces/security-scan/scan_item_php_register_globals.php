<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_php_register_globals    extends WPH_security_scan_item
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
                    return 'php_register_globals';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'PHP register_globals',
                                        'icon'          =>  'dashicons-admin-generic',
                                        
                                        'help'          =>  __("When register_globals is enabled, PHP will automatically create variables in the global scope for any value passed in GET, POST or COOKIE. This, combined with the use of variables without initialization, has led to numerous security vulnerabilities.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  20,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $register_globals = (bool)ini_get( 'register_globals' );
                    
                    if ( $register_globals    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;
                                                                        
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The register_globals setting is enabled. This deprecated PHP feature can automatically register user input as global variables, creating serious security risks such as variable injection. It is strongly recommended to disable register_globals, as it is unsafe and no longer supported in modern PHP versions.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'To fix this security issue, change the php.ini', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>register_globals = "off"</li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'You can disable this within .htaccess', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>php_flag register_globals off</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';
                                                                 
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The register_globals is Off.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; When register_globals is enabled, PHP will automatically create variables in the global scope for any value passed in GET, POST or COOKIE. This, combined with the use of variables without initialization, has led to numerous security vulnerabilities.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
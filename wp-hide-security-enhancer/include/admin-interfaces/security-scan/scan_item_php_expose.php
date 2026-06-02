<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_php_expose    extends WPH_security_scan_item
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
                    return 'php_expose';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'PHP expose',
                                        'icon'          =>  'dashicons-admin-generic',
                                        
                                        'help'          =>  __("When the expose_php directive is enabled, PHP includes critical pieces of information within the HTTP response X-Powered-By header when a page is requested.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $expose_php = (bool)ini_get( 'expose_php' );
                    
                    if ( $expose_php    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;
                                                                        
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The expose_php setting is currently enabled on your server. This allows PHP to disclose its version in HTTP headers, making it easier for attackers to identify potential vulnerabilities based on outdated software. For improved security, it is recommended to disable expose_php in your configuration.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'To fix this security issue, change the php.ini', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>expose_php = "off"</li>
                                                                        </ul>
                                                                    </div>
                                                                    
                                                                    <div class="description">
                                                                        <p>' . __( 'You can disable this within .htaccess', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>php_flag expose_php off</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';
                                                                        
                                                    }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The expose_php is Off.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; The expose_php setting is currently enabled on your server. This allows PHP to disclose its version in HTTP headers, making it easier for attackers to identify potential vulnerabilities based on outdated software. For improved security, it is recommended to disable expose_php in your configuration. &quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
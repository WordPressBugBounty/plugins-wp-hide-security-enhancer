<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_wp_version    extends WPH_security_scan_item
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
                    return 'wp_version';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                    'title'         =>  'WordPress Version',
                                    'icon'          =>  'dashicons-wordpress-alt',
                                    
                                    'help'          =>  __("WordPress is a permanent evolving software with regularly released security fixes. The core updates ensure the safety and efficiency of the WordPress system.
                                                            WordPress updates often include security fixes. It’s an ongoing battle since hackers find vulnerabilities all the time. It’s important to keep WordPress up to date to get the latest protections from new types of attacks.",    'wp-hide-security-enhancer'),
                                    
                                    'score_points'  =>  5,
                                    
                                    'callback'      =>  array ( $this, 'scan' ),
                                    'use_transient' =>  TRUE
                                    );
                }
                
            
            function scan()
                {
                    global $wp_version;
                    
                    $_JSON_response     =   array();
                    $wp_latest          =   FALSE;
                    
                    $_JSON_response['info']  =   __( 'Using Version: ', 'wp-hide-security-enhancer' ) . $wp_version;
                    
                    $response       =   wp_remote_get( 'https://api.wordpress.org/core/version-check/1.7/', array( 'sslverify' => false, 'timeout' => 10 )  );
                    
                    $http_response  =   FALSE;
                    if ( ! is_wp_error( $response ) )
                        $http_response  =   $response['http_response'];
                    
                    if ( ! is_array( $response )    ||  ! is_object( $http_response )   ||  $http_response->get_status() !=  200 )
                        {
                            $_JSON_response['status']       =   FALSE;
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'Unable to connect with WordPress API. Try again later.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                            $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary" target="_blank" href="'. network_admin_url ( 'update-core.php' ) .'">Fix</a>',
                                                                        'ignore'    =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; WordPress is a permanent evolving software with regularly released security fixes. The core updates ensure the safety and efficiency of the WordPress system.
                                                            WordPress updates often include security fixes. It’s an ongoing battle since hackers find vulnerabilities all the time. It’s important to keep WordPress up to date to get the latest protections from new types of attacks..&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );
                            
                            return $this->return_json_response( $_JSON_response );
                        }
                    
                    $response_body  =   json_decode ( $response['body'] );
                    if ( $response_body->offers[0] )
                        {
                            $block  =   $response_body->offers[0];
                            $wp_latest  =   $block->version;
                        }
                        
                    if ( $wp_latest )
                        {
                            if ( version_compare ( $wp_version, $wp_latest, '==' ) )
                                {
                                    $_JSON_response['status']       =   TRUE;
                                    $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'You are up to date with the latest Wordpress version.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                                }
                                else
                                {
                                    $_JSON_response['status']       =   FALSE;
                                    $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span> An updated version ', 'wp-hide-security-enhancer' ) . $wp_latest . __(' of WordPress is available.', 'wp-hide-security-enhancer' );
                                    $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'A newer version of WordPress (', 'wp-hide-security-enhancer' ) . $wp_latest . __(') ) is available. Keeping your WordPress installation up to date is essential for security, performance, and compatibility.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                                    
                                }    
                                
                            $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary fix" target="_blank" href="'. network_admin_url ( 'update-core.php' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; WordPress is a permanent evolving software with regularly released security fixes. The core updates ensure the safety and efficiency of the WordPress system.
                                                            WordPress updates often include security fixes. It’s an ongoing battle since hackers find vulnerabilities all the time. It’s important to keep WordPress up to date to get the latest protections from new types of attacks..&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );
                        }
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_old_plugins    extends WPH_security_scan_item
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
                    return 'old_plugins';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'Old Plugins',
                                        'icon'          =>  'dashicons-admin-plugins',
                                        
                                        'help'          =>  __("Old WordPress plugins can do damage to your website. Vulnerabilities are found within plugins all the time. Unmaintained code drastically increase the risk, as there are no patches for known issues. 
                                                                Inconsistent updates can lead to serious security issues and compatibility problems, and land you in technical debt.
                                                                This will check for plugins with more than a year since their last update.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        
                                        'use_transient' =>  TRUE
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();

                    $found_old  =   array();
                    
                    $active_plugins = get_option( 'active_plugins' );
                    $all_plugins    = apply_filters( 'all_plugins', get_plugins() );
                    
                    foreach ( $active_plugins   as  $active_plugin )
                        {
                            list ( $plugin_slug, $file )    =   explode ( '/' , $active_plugin );
                            if ( empty ( $plugin_slug ) )
                                continue;
                            
                            $response       =   wp_remote_get( 'https://api.wordpress.org/plugins/info/1.0/' . $plugin_slug . '.json' , array( 'sslverify' => false, 'timeout' => 10 )  );
                            $http_response  =   $response['http_response'];
                            
                            if ( ! is_array( $response )    ||  ! is_object( $http_response )   ||  $http_response->get_status() !=  200 )
                                continue;
                                                            
                            $response_body  =   json_decode ( $response['body'] );
                            
                            $last_update    =   strtotime ( $response_body->last_updated );
                            if ( $last_update > strtotime ( "-1 year") )
                                continue;
                                                        
                            $found_old[ $plugin_slug ]  =   array (
                                                                    'name'          =>  $response_body->name,
                                                                    'last_updated'  =>  $response_body->last_updated,
                                                                    );
                            if ( isset ( $response_body->screenshots )  &&  isset ( $response_body->screenshots->{1} ) )
                                $found_old[ $plugin_slug ]['screenshot']    =   $response_body->screenshots->{1}->src;
                                else  
                                $found_old[ $plugin_slug ]['screenshot']    =   'https://ps.w.org/classic-editor/assets/icon-256x256.png';
                        }
                    
                    if ( $found_old )
                        $_JSON_response['info']  =   __( 'Found old plugins: ', 'wp-hide-security-enhancer' ) . count ( $found_old  );

                    if ( count ( $found_old ) > 0   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span>The following plugins are very old and appear unmaintained:', 'wp-hide-security-enhancer' );
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The following plugins appear to be outdated and no longer actively maintained. Unmaintained plugins are a significant security risk, as they may contain unresolved vulnerabilities and compatibility issues. It is recommended to replace them with actively supported alternatives or remove them if no longer needed.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="error_log">
                                                                        <ul>
                                                                    ';

                            foreach ( $found_old   as  $plugin_slug    =>  $plugin_data )
                                {
                                    
                                    $_JSON_response['description']  .=  '<li>';
                                    
                                    $_JSON_response['description']  .=   '<img class="icon" src="'. $plugin_data['screenshot'].'" /> ';
                                                                                
                                    $_JSON_response['description']  .=   '<b>' . $plugin_data['name'] .'</b><br />' . __( ' Last updated on ', 'wp-hide-security-enhancer' ) . $plugin_data['last_updated'];
                                    
                                    $_JSON_response['description']  .=  '</li>';
                                    
                                }
                                
                            $_JSON_response['description']  .=   '</ul></div></div>';
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>There are no Old Plugins.', 'wp-hide-security-enhancer' );
                        }  
                        
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url ( 'plugins.php' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; Old WordPress plugins can do damage to your website. Vulnerabilities are found within plugins all the time. Unmaintained code drastically increase the risk, as there are no patches for known issues. Inconsistent updates can lead to serious security issues and compatibility problems, and land you in technical debt. This will check for plugins with more than a year since their last update..&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
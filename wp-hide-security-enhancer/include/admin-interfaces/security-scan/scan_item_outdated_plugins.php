<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_outdated_plugins    extends WPH_security_scan_item
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
                    return 'outdated_plugins';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'Outdated Plugins',
                                        'icon'          =>  'dashicons-admin-plugins',
                                        
                                        'help'          =>  __("Keeping your plugins up to date is important for the stability and security of your WordPress site. It also lets you take advantage of any new features the plugin's developers have added.
                                                                A key concept of updating WordPress core, themes, and plugins is to protect your site from the possible vulnerabilities that allow a hacker to compromise your site. ",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  15,
                                        
                                        'callback'      =>  'scan_item_outdated_plugins',
                                        'use_transient' =>  TRUE
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();

                    wp_update_plugins();
                    
                    $update_plugins     = get_site_transient('update_plugins');
                    $found_outdated =   array();
                    if ( $update_plugins && is_array( $update_plugins->response ) && count ( $update_plugins->response ) > 0 ) 
                        $found_outdated =   $update_plugins->response;
                    
                    if ( is_array( $found_outdated ) && count ( $found_outdated ) > 0 )
                        $_JSON_response['info']  =   __( 'Found outdated plugins: ', 'wp-hide-security-enhancer' ) . count ( $found_outdated  );

                    if ( is_array( $found_outdated ) && count ( $found_outdated ) > 0   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The following plugins on your site are outdated. Running outdated plugins may expose your site to known security vulnerabilities and compatibility issues. It is strongly recommended to update them to the latest available versions as soon as possible.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                                                                                                                                    
                                                                 ';
                            
                            $all_plugins = apply_filters( 'all_plugins', get_plugins() );
                            
                            $_JSON_response['description']  .=  '<ul>';
                            foreach ( $found_outdated   as  $plugin_slug    =>  $plugin_data )
                                {
                                    $plugin_data    =   array_merge ( (array)$plugin_data, $all_plugins[$plugin_slug]);       
                                    
                                    $_JSON_response['description']  .=  '<li>';
                                    
                                    if ( isset ( $plugin_data['icons'] )    &&  isset ( $plugin_data['icons']['2x'] ) )
                                        $_JSON_response['description']  .=   '<img class="icon" src="'. $plugin_data['icons']['2x'].'" /> ';
                                        else
                                        $_JSON_response['description']  .=   '<img class="icon" src="https://ps.w.org/classic-editor/assets/icon-256x256.png" /> ';
                                                                                
                                    $_JSON_response['description']  .=   '<b>' . $plugin_data['Name'] .'</b><br />' . __( ' Upgrade from ', 'wp-hide-security-enhancer' ) . $plugin_data['Version'] .  __( ' to ', 'wp-hide-security-enhancer' ) . $plugin_data['new_version'];
                                    
                                    $_JSON_response['description']  .=  '</li>';
                                    
                                }
                            $_JSON_response['description']  .=  '</ul></div>';
                                                        
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>All plugins are Up to Date.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url ( 'plugins.php' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot;The following plugins on your site are outdated. Running outdated plugins may expose your site to known security vulnerabilities and compatibility issues. It is strongly recommended to update them to the latest available versions as soon as possible.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_db_debug    extends WPH_security_scan_item
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
                    return 'db_debug';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'Database Debug',
                                        'icon'          =>  'dashicons-code-standards',
                                        
                                        'help'          =>  __("Debugging PHP code is part of any project, but WordPress comes with specific debug systems designed to simplify the process as well as standardize code across the core, plugins and themes.
                                                                On production sites, the debug should be disabled to avoid exposing paths and other pieces of information related to the site. ",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        
                                        'callback'      =>  'scan_item_db_debug',
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();

                    global $wpdb;
                    
                    $_JSON_response['info']  =   __( 'Current value: ', 'wp-hide-security-enhancer' ) . ( $wpdb->show_errors  === TRUE ? 'TRUE' : 'FALSE' );

                    if ( $wpdb->show_errors    === TRUE   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'Database debugging is currently enabled. This setting may expose database queries and sensitive information on your site. Check your', 'wp-hide-security-enhancer' ) . ' <code>wp-config.php</code> ' . __( 'file and disable debugging by setting WP_DEBUG and WP_DEBUG_DISPLAY (if defined) to false, or by commenting out their declarations.', 'wp-hide-security-enhancer' ) . '</p>
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
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The database debug is disabled.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot;Database debugging is currently enabled. This setting may expose database queries and sensitive information on your site. Check your wp-config.php file and disable debugging by setting WP_DEBUG and WP_DEBUG_DISPLAY (if defined) to false, or by commenting out their declarations.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_database_prefix    extends WPH_security_scan_item
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
                    return 'database_prefix';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'Database Prefix',
                                        'icon'          =>  'dashicons-database',
                                        
                                        'help'          =>  __("WordPress security is a serious matter and you can improve it by changing the WordPress database prefix. A WordPress database contains all of the information for your website, which makes it a prime target for hackers. 
                                                                By default, the WordPress database prefix is “wp_” and is quite easy to locate and target.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    global $wpdb;
                    
                    $_JSON_response['info']  =   __( 'Current value: ', 'wp-hide-security-enhancer' ) . $wpdb->prefix;

                    if ( $wpdb->prefix    ==   'wp_'   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The default WordPress database prefix is in use. Using a predictable prefix like wp_ can make your database more susceptible to automated attacks targeting common table names. For improved security, consider changing the database prefix to a unique value.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="row labels">
                                                                        <div class="item">
                                                                            <div class="label">' . __( 'Expected', 'wp-hide-security-enhancer' ) .'</div>
                                                                            <div class="value">random</div>
                                                                        </div>

                                                                        <div class="item">
                                                                            <div class="label">' . __( 'Current', 'wp-hide-security-enhancer' ) . '</div>
                                                                            <div class="value">wp_</div>
                                                                        </div>
                                                                    </div>
                                                                                                                                                
                                                                 </div>';
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The database prefix use a custom name.', 'wp-hide-security-enhancer' );
                        }
                    
                    $_JSON_response['actions']      =   array (
                                                                        'read_more'       =>  '<a class="button" target="_blank" href="https://wp-staging.com/3-ways-to-change-the-wordpress-database-prefix-method-simplified/">Read More</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot;The default WordPress database prefix is in use. Using a predictable prefix like wp_ can make your database more susceptible to automated attacks targeting common table names. For improved security, consider changing the database prefix to a unique value.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
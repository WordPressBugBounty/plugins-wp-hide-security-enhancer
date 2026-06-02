<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_remove_headers    extends WPH_security_scan_item
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
                    return 'hide_remove_headers';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('Remove Environment Headers',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("Remove the X-Powered-By and Server Headers if set. This type of header information discloses important details regarding your server environment.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $found_issue        =   FALSE;
                    $found_headers      =   array();
                                      
                    if ( $this->wph->security_scan->remote_headers )
                        {
                            foreach ( $this->wph->security_scan->remote_headers->getAll() as $header_name =>  $header_value )
                                {
                                    if ( stripos( $header_name, 'x-powered-by' )    === 0   )
                                        {
                                            $found_headers[]    =   'x-powered-by';
                                            $found_issue    =   TRUE;
                                        }
                                    if ( stripos( $header_name, 'server' )    === 0   )
                                        {
                                            $found_headers[]    =   'server';
                                            $found_issue    =   TRUE;
                                        }
                                }
                        }
                        else
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span>Your site headers still contain some valuable pieces of information regarding your environment.', 'wp-hide-security-enhancer' );
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'Your site headers still contain some valuable pieces of information regarding your environment.', 'wp-hide-security-enhancer' ) . '</p>
                                                                    </div>
                                                                    
                                                                    <div class="error_log">
                                                                        <ul>
                                                                    ';
                            
                            foreach ( $found_headers   as  $found_header )
                                {
                                    $_JSON_response['description']  .=  "<li><span class='info'>[" . __( 'Found', 'wp-hide-security-enhancer' ) . "]</span> " . ucfirst ( $found_header ) . '</li>';
                                }
                                
                            $_JSON_response['description']  .=   '</ul></div></div>';
                                
                            if ( $this->wph->security_scan->remote_started  &&  $this->wph->security_scan->remote_errors   !== FALSE )
                                $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __('Unable to complete this security task as an error occoured', 'wp-hide-security-enhancer' ) . ': <b>' .$this->wph->security_scan->remote_errors . '</b></p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                            
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>There are no headers containing valuable pieces of information regarding your environment.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url ( 'admin.php?page=wp-hide-general&component=headers' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; Remove the X-Powered-By and Server Headers if set. This type of header information discloses important details regarding your server environment..&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
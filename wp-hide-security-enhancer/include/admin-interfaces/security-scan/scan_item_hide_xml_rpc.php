<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_xml_rpc    extends WPH_security_scan_item
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
                    return 'hide_xml_rpc';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('XML-RPC',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("XML-RPC is a remote procedure call (RPC) protocol which uses XML to encode its calls and HTTP as a transport mechanism. This service allow other applications to talk to your WordPress site.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $found_issue        =   FALSE;
                    
                    $new_xml_rpc_path           =   $this->wph->functions->get_module_item_setting('new_xml_rpc_path');
                    $disable_xml_rpc_auth       =   $this->wph->functions->get_module_item_setting('disable_xml_rpc_auth');
                    $disable_xml_rpc_service    =   $this->wph->functions->get_module_item_setting('disable_xml_rpc_service');
                    
                    if ( empty ( $new_xml_rpc_path )    &&  ( empty ( $disable_xml_rpc_auth )   ||  $disable_xml_rpc_auth   ==  'no' ) &&  ( empty ( $disable_xml_rpc_service )   ||  $disable_xml_rpc_service   ==  'no' ) )
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                            <div class="description">
                                                                                <p>' . __( 'The XML-RPC module has not been customised.', 'wp-hide-security-enhancer' ) . '</p>
                                                                            </div>
                                                                                                                                                                                            
                                                                         </div>';
                            
                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The XML-RPC appears properly configured.', 'wp-hide-security-enhancer' );
                        }  
                        
                    $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url ( 'admin.php?page=wp-hide&component=xml-rpc' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; XML-RPC is a remote procedure call (RPC) protocol which uses XML to encode its calls and HTTP as a transport mechanism. This service allow other applications to talk to your WordPress site.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
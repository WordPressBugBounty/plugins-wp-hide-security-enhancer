<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general_core extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Core";
                }
            
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_directory_listing',
                                                                                                                
                                                                    'input_type'    =>  'radio',
                                 
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower')
                                                                    
                                                                    );
     
                                                                                      
                    return $this->module_settings;   
                }
                

        function set_module_components_description( $component_settings )
                {

                    foreach ( $component_settings   as  $component_key  =>  $component_setting )
                        {
                            if ( ! isset ( $component_setting['id'] ) )
                                continue;
                            
                            switch ( $component_setting['id'] )
                                {
                                    case 'disable_directory_listing' :
                                                                $component_setting =   array_merge ( $component_setting , array(
                                                                                                                                    'label'         =>  __('Disabling Directory Listing',    'wp-hide-security-enhancer'),
                                                                                                                                    'description'   =>  __('Disabling Directory Listing for server folders.',  'wp-hide-security-enhancer'),
                                                                                                                                    
                                                                                                                                    'help'          =>  array(
                                                                                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Disabling Directory Listing',    'wp-hide-security-enhancer'),
                                                                                                                                                                'description'               =>  __("By leveraging a rewrite directive, this feature prevents unauthorized users from browsing server directories, ensuring sensitive files remain inaccessible.",    'wp-hide-security-enhancer') .
                                                                                                                                                                                                "<br /> " . __("Once enabled, the plugin injects the directive into your .htaccess / configuration file ( depending on server type ), blocking directory index views without manual edits. This simple addition fortifies your website by eliminating potential exposure of directory contents, reducing vulnerabilities. Protect assets and maintain a professional appearance by completely hiding file listings.",    'wp-hide-security-enhancer') .
                                                                                                                                                                                                "<br /> " . __("Benefits:",    'wp-hide-security-enhancer') .
                                                                                                                                                                                                "<ul><li>" . __("Enhances security by hiding directory structure",    'wp-hide-security-enhancer') . "</li>".
                                                                                                                                                                                                "<li>" . __("Protects sensitive files and assets",    'wp-hide-security-enhancer') . "</li></ul>" ,
                                                                                                                                                                'option_documentation_url'  =>  'https://wp-hide.com/documentation/general-core/'
                                                                                                                                                                ),

                                                                                                                                    'options'       =>  array(
                                                                                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                                                                                ),
                                                                                                                                ) );
                                                                break;
          
                                   
                                                     
                                }
                                
                            $component_settings[ $component_key ]   =   $component_setting;
                        }
                    
                    return $component_settings;
                    
                }
                    
                
                
            function _callback_saved_disable_directory_listing($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    $processing_response    =   array();
                                                         
                    $rewrite                            =  '';
                                        
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        {
                            $rewrite    .=      "\nOptions -Indexes";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        {
                            
                        }
                    
                    $processing_response['rewrite'] =   $rewrite;                                       
                                      
                    return  $processing_response;
                    
                } 
        


        }
        
?>
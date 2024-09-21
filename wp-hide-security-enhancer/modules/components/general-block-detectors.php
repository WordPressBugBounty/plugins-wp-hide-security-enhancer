<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general_block_detectors extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Block Detectors";
                }
                                    
            function get_module_settings()
                {
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_detectors',
                                                                    'label'         =>  __('Block Theme / Plugin detectors.',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block common Theme / Plugin detectors and scanners.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block Theme / Plugin detectors',    'wp-hide-security-enhancer'),
                                                                                                'description'               =>  __("Enhance your website's privacy and security with the Block Theme Detectors feature. This tool prevents known user agents and IP addresses associated with popular theme detectors from accessing your site's design and theme-related information. By doing so, it keeps your creative choices private and reduces the risk of targeted attacks exploiting specific theme vulnerabilities." , 'wp-hide-security-enhancer')
                                                                                                                                . "<br /><strong>" . __( "Key Benefits", 'wp-hide-security-enhancer') . ":</strong>
                                                                                                                                        <ul>
                                                                                                                                             <li>" . __(  "Privacy: Protect your website's theme details from being copied or analyzed", 'wp-hide-security-enhancer') . "</li>
                                                                                                                                             <li>" . __('Security: Minimize exposure to potential threats',    'wp-hide-security-enhancer') .".</li>
                                                                                                                                             <li>" . __('Performance: Optimize server resources by blocking unnecessary traffic',    'wp-hide-security-enhancer') .".</li>
                                                                                                                                             <li>" . __('Competitive Edge: Maintain a unique brand identity',    'wp-hide-security-enhancer') .".</li>
                                                                                                                                        </ul>",
                                                                                                'option_documentation_url'  =>  'https://wp-hide.com/documentation/block-theme-plugin-detectors/'
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  70
                                                                    );
                    
                   
                                                                    
                    return $this->module_settings;   
                }
                
            function _callback_saved_block_detectors ( $saved_field_data )
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE; 
                        
                    $empty_file =   $this->wph->functions->get_url_path( WP_PLUGIN_URL    );
                    $rewrite_to =   $this->wph->functions->get_rewrite_to_base( trailingslashit( $empty_file ) . 'wp-hide-security-enhancer/router/empty.html', TRUE, FALSE );    
                                            
                    $processing_response    =   array();
                          
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                               
                            $processing_response['rewrite'] = '
RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in.*$ [NC]
RewriteCond %{REMOTE_ADDR} ^167\.99\.233\.\d+$ [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (builtwith|gochyu|isitwp|mshots|scanwp|wpthemedetector|whatcms|wapalyzer|wpdetector) [NC]
RewriteRule ^ ' . $rewrite_to . ' [END]';
           
                        }
                            
                    if($this->wph->server_web_config   === TRUE)
                        {

                        }
                    
                    if($this->wph->server_nginx_config   === TRUE)           
                        {
                            
                        }
                                
                    return  $processing_response;   
                    
                }
                
            
        
                
        }
?>
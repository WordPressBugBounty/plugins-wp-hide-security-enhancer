<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general_robots_txt extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Robots.txt";
                }
                                        
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_robots_txt',
                                                                                                     
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
                                    case 'disable_robots_txt' :
                                                                $component_setting = array_merge( $component_setting, array(
                                                                                                                            'label'         => __('Replace default admin URLs within Robots.txt', 'wp-hide-security-enhancer'),
                                                                                                                            'description'   => __('Replace any default WordPress admin URLs automatically generated in robots.txt with random values, preventing the disclosure of your customized admin location.', 'wp-hide-security-enhancer'),
                                                                                                                            'help'          => array(
                                                                                                                                'title'                     => __('Help', 'wp-hide-security-enhancer') . ' - ' . __('Replace default admin URLs within Robots.txt', 'wp-hide-security-enhancer'),
                                                                                                                                'description'               => __("The robots.txt file helps search engine crawlers understand which areas of your site should or should not be accessed. By default, WordPress includes references to the standard admin URLs such as <code>/wp-admin/</code> and <code>admin-ajax.php</code>.", 'wp-hide-security-enhancer') .
                                                                                                                                                                    "<br /><br />" .
                                                                                                                                                                    __("When WP Hide customizes the admin location, exposing the new URL through robots.txt would reveal the protected endpoint. To prevent this, enabling this option replaces the default WordPress admin URL references with random values instead of your customized admin URL, ensuring that the new location remains undisclosed.", 'wp-hide-security-enhancer') .
                                                                                                                                                                    "<br /><br />" .
                                                                                                                                                                    __("Sample robots.txt URL:", 'wp-hide-security-enhancer') .
                                                                                                                                                                    "<br /><code>https://-domain-name-/robots.txt</code>",
                                                                                                                                 'option_documentation_url'  => 'https://wp-hide.com/documentation/general-html-robots-txt/',
                                                                                                                                 'ai_question'               => 'Help me understand the "Replace default admin URLs within Robots.txt" option in the WP Hide plugin',
                                                                                                                            ),
                                                                                                                             'options'       => array(
                                                                                                                                'no'    => __('No', 'wp-hide-security-enhancer'),
                                                                                                                                'yes'   => __('Yes', 'wp-hide-security-enhancer'),
                                                                                                                            ),
                                                                                                                        ) );
                                                                break;
                                                     
                                }
                                
                            $component_settings[ $component_key ]   =   $component_setting;
                        }
                    
                    return $component_settings;
                    
                }
                
                    
            function _init_disable_robots_txt($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    add_action( 'robots_txt', array($this, 'disable_robots_txt' ), 999, 2);
                }
                
            
            function disable_robots_txt( $output, $public )
                {
                    $search_for = '/wp-admin/';

                    // Generate a random replacement (8 lowercase characters)
                    $random_word = '/' . wp_generate_password( 8, false, false ) . '/';

                    $output = str_replace( $search_for, $random_word, $output );

                    return $output;
                    
                }
                    
         
        }
?>
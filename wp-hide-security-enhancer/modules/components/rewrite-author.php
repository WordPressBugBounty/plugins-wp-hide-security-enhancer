<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_author extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Author";
                }
                                                
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'author',
                                                                                                                                 
                                                                        'value_description' =>  'e.g. contributor',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                        'processing_order'  =>  60
                                                                        );
                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'author_disable_archive',
                                                  
                                                                        'input_type'    =>  'radio',
                                                             
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  62
                                                                        
                                                                        );
                                                                        
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'author_block_default',
                                                  
                                                                        'input_type'    =>  'radio',
                                                             
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  63
                                                                        
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
                                    case 'author' :
                                                                $component_setting =   array_merge ( $component_setting , array(
                                                                                                                                    'label'         =>  __('New Author Path',    'wp-hide-security-enhancer'),
                                                                                                                                    'description'   =>  __('The default path is set to /author/',    'wp-hide-security-enhancer'),
                                                                                                                                    
                                                                                                                                    'help'          =>  array(
                                                                                                                                                                    'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Author Path',    'wp-hide-security-enhancer'),
                                                                                                                                                                    'description'               =>  __("An author URL display all posts associated to a particular author. The default URL format is:",    'wp-hide-security-enhancer') ."<br />  <br />
                                                                                                                                                                                                        <code>https://-domain-name-/author/author-name/</code>
                                                                                                                                                                                                        <br /><br /> " . __("By using a value of 'contributor' this become:",    'wp-hide-security-enhancer') ."<br />
                                                                                                                                                                                                        <code>https://-domain-name-/contributor/author-name/</code>",
                                                                                                                                                                    'option_documentation_url'  =>  'https://wp-hide.com/documentation/rewrite-author/',
                                                                                                                                                                    ),
                                                                                                                                ) );
                                                                break;
                                                                
                                    case 'author_disable_archive' :
                                                                $component_setting =   array_merge ( $component_setting , array(
                                                                                                                                    'label'         =>  __('Prevent Access to Author Archives',    'wp-hide-security-enhancer'),
                                                                                                                                    'description'   =>  __('Prevent Access to Author Archives via User IDs',    'wp-hide-security-enhancer'),
                                                                                                                                    
                                                                                                                                    'help'          =>  array(
                                                                                                                                                                    'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Prevent Access to Author Archives',    'wp-hide-security-enhancer'),
                                                                                                                                                                    'description'               =>  __("By default, WordPress generates author archive pages using URLs like ",    'wp-hide-security-enhancer') .
                                                                                                                                                                                                        "<code>yoursite.com/?author=ID</code>" .
                                                                                                                                                                                                        __(". This behavior can be exploited by attackers who repeatedly request URLs such as ",    'wp-hide-security-enhancer') .
                                                                                                                                                                                                        "<code>?author=1, ?author=2</code>" .
                                                                                                                                                                                                        __(". and so on, until they find valid user IDs—revealing active usernames on your site.",    'wp-hide-security-enhancer') . "<br />  <br />" .
                                                                                                                                                                                                        __("To enhance security, it's best to completely disable access to these types of URLs, especially since author archive pages often serve little or no purpose on many sites.",    'wp-hide-security-enhancer')  . "<br />  <br />" .
                                                                                                                                                                                                        __("Even if your permalink settings are not set to the default (i.e., 'Plain'), WordPress will still redirect ?author=ID URLs to the corresponding author archive page, if it exists. Therefore, additional measures are necessary to block these redirects and prevent username enumeration.",    'wp-hide-security-enhancer') ,
                                                                                                                                                                    'option_documentation_url'  =>  'https://wp-hide.com/documentation/rewrite-author/',
                                                                                                                                                                    ),
                                                                                                                                                                    
                                                                                                                                    'options'       =>  array(
                                                                                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                                                                                ),
                                                                                                                                ) );
                                                                break;
                                                                
                                                                
                                    case 'author_block_default' :
                                                                $component_setting =   array_merge ( $component_setting , array(
                                                                                                                                    'label'         =>  __('Block default',    'wp-hide-security-enhancer'),
                                                                                                                                    'description'   =>  __('Block default /author/ when using custom one.',    'wp-hide-security-enhancer') . '<br />'.__('Apply only if ',    'wp-hide-security-enhancer') . '<b>New Author Path</b> ' . __('is not empty.',    'wp-hide-security-enhancer'),
                                                                                                                                    
                                                                                                                                    'help'          =>  array(
                                                                                                                                                                    'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block default',    'wp-hide-security-enhancer'),
                                                                                                                                                                    'description'               =>  __("After changing the default author, the old url is still accessible, this provide a way to block it.<br />The functionality apply only if <b>New Author Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                                                                                    'option_documentation_url'  =>  'https://wp-hide.com/documentation/rewrite-author/'
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
                
                
            function _init_author( $saved_field_data )
                {
                    add_filter('author_rewrite_rules',      array( $this, 'author_rewrite_rules'), 999);
                    
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'author';
                    $replacement    =   trailingslashit(    home_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            
            /**
            * Rewrite the default Author url
            * 
            * @param mixed $author_rewrite
            */
            function author_rewrite_rules( $author_rewrite )
                {
                    
                    $new_author_path        =   $this->wph->functions->get_module_item_setting('author');
                    
                    if( empty( $new_author_path ) )
                        return $author_rewrite;
                        
                    $author_block_default   =   $this->wph->functions->get_module_item_setting('author_block_default');                    
                    
                    $new_rules              =   array();
                    foreach ( $author_rewrite   as  $key    =>  $value )
                        {
                            $new_rules[ str_replace( 'author/', $new_author_path .'/' , $key ) ]    =   $value;    
                        }
                        
                    if  ( $author_block_default ==  'yes')
                        $author_rewrite =   $new_rules;
                        else
                        $author_rewrite =   array_merge ( $author_rewrite, $new_rules );
                    
                    return $author_rewrite;
                      
                }
                
                
            function _callback_saved_author_disable_archive($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                                           
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text    =  'RewriteCond %{QUERY_STRING} author=\d+' . "\n";
                            $text   .=  'RewriteRule ^ '.  $rewrite_to .'?wph-throw-404 [L]';
                        }
                        
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            
        }
?>
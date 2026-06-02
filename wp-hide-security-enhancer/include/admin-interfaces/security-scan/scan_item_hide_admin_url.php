<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_admin_url    extends WPH_security_scan_item
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
                    return 'hide_admin_url';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('New Admin Url',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("Despite the flexibility of WordPress framework, there are few ways to configure the admin login url customization for making a bit safer against unauthorized access and brute force attempts. All methods are not provided out of the box through WordPress core but require custom code to make it happen.
                                                                <br />This feature provide an easy way to change the default /wp-admin/ to a different slug.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  20,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response = array();
                    
                    $found_issue = FALSE;
                    
                    $option       =   $this->wph->functions->get_module_item_setting('admin_url');
                    
                    if (empty($option) || $option == 'no')
                        $found_issue = TRUE;

                    if ($found_issue)
                        {
                            $_JSON_response['status'] = FALSE;
                            
                            $_JSON_response['description'] = '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __('Mapping a new admin URL instead of the default one helps prevent brute force login attempts and unauthorized access.', 'wp-hide-security-enhancer') . '</p>
                                                                    </div>
                                                                                                                                                                                    
                                                                 </div>';
                            
                            
                        }
                        else
                        {
                            $_JSON_response['status'] = TRUE;
                            $_JSON_response['description'] = __('<span class="dashicons dashicons-yes"></span>The option appears properly configured.', 'wp-hide-security-enhancer');
                        }
                        
                    $_JSON_response['actions'] = array(
                                                                'fix'     => '<a class="button-primary tips" original-title="Go to a Fix" href="'. network_admin_url('admin.php?page=wp-hide-admin&component=admin-url') .'">Fix</a>',
                                                                'ignore'  => '//--post-generated--',
                                                                'restore' => '//--post-generated--',
                                                                'help'    => '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot; Mapping a new admin URL instead of the default one helps prevent brute force login attempts and unauthorized access. &quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                            );  
                        
                    return $this->return_json_response($_JSON_response);
                
                }    
            
        }
        
        
?>
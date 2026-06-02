<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_disable_file_edit    extends WPH_security_scan_item
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
                    return 'disable_file_edit';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  'Theme/Plugin File Editor',
                                        'icon'          =>  'dashicons-code-standards',
                                        
                                        'help'          =>  __("The WordPress theme/plugin file editor lets you open files from the site. It displays the file content on the text editor allowing changes to the code, directly on the dashboard.
                                                                <br />Unless this is a development instance, it should be disabled.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  5,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $_JSON_response['info']  =   __( 'Current value: ', 'wp-hide-security-enhancer' ) . ( defined ( 'DISALLOW_FILE_EDIT' )  &&  DISALLOW_FILE_EDIT  === TRUE ? 'TRUE' : 'FALSE' );

                    if ( ! defined ( 'DISALLOW_FILE_EDIT' ) ||  DISALLOW_FILE_EDIT    === FALSE   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   '<div class="vulnerability-report">

                                                                    <div class="description">
                                                                        <p>' . __( 'The built-in Theme/Plugin File Editor is currently enabled. This feature allows direct modification of theme and plugin files from the WordPress dashboard, which can pose a security risk if unauthorized access is gained.', 'wp-hide-security-enhancer' ) . '</p>
                                                                        <p>' . __( 'To improve security, disable file editing by adding or updating the following line in your', 'wp-hide-security-enhancer' ) . ' <code>wp-config.php</code> ' . __( 'file', 'wp-hide-security-enhancer' ) . ':</p>
                                                                    </div>
                                                                    
                                                                    <div class="code_example">
                                                                        <ul>
                                                                            <li>define ( \'DISALLOW_FILE_EDIT\', TRUE );</li>
                                                                        </ul>
                                                                    </div>
                                                                            
                                                                 </div>';

                            
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The file editor is disabled.', 'wp-hide-security-enhancer' );
                        }
                        
                    $_JSON_response['actions']      =   array (
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        'help'              =>  '<a class="button tips" original-title="Get Help from AI" target="_blank" href="https://chat.openai.com/?q=Help me understand the &quot;The built-in Theme/Plugin File Editor is currently enabled. This feature allows direct modification of theme and plugin files from the WordPress dashboard, which can pose a security risk if unauthorized access is gained.&quot;. This is a Scan Item in WP Hide plugin">AI Help</a>',
                                                                        );  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
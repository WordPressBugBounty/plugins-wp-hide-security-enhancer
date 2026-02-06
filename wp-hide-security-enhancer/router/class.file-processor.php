<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_File_Processor
        {
            var $action;
            var $file_path;
            var $replacement_path;
            
            var $full_file_path;
            
            var $allowed_file_type  =   array('css');
            
            var $allowed_paths      =   array();
            
            var $environment        =   array();
            
            function __construct( $action, $file_path, $replacement_path )
                {
                    
                    $this->action           =   $action;
                    $this->file_path        =   preg_replace('/\.+[\\/]+/', '', $file_path );
                    $this->replacement_path =   preg_replace('/\.+[\\/]+/', '', $replacement_path );
                    
                    $this->define_wp_constants();
                    
                    $this->load_environment();
                    
                    //if not able to load the environment, exit
                    if  ( empty ( $this->environment ) )
                        die();
                                        
                    $normalize_abspath      =   $this->normalize_path ( ABSPATH );
                    $normalize_abspath      =   $this->normalize_path ( $normalize_abspath );
                    
                    //exclude any wordpress directory if exists
                    if( $this->environment->wordpress_directory !=  ''  &&  $this->environment->wordpress_directory !=  '/')
                        {
                            $normalize_abspath  =   substr($normalize_abspath, 0,  -1 * strlen( $this->environment->wordpress_directory ));;   
                        }
                    
                    //append doc root to path 
                    $this->full_file_path   =   str_replace( ltrim( $this->environment->site_relative_path , '\/'), "",  $normalize_abspath); 
                    $this->full_file_path   =   $this->normalize_path ( $this->full_file_path );
                    $this->full_file_path   =   rtrim( $this->full_file_path , '/') . "/";
                    
                    $this->full_file_path  .=   ltrim($this->file_path, '\/');
                    $this->full_file_path   =   $this->normalize_path ( $this->full_file_path );

                    //check if file exists
                    if (!file_exists($this->full_file_path))
                        die();
                        
                    //allow only style files
                    $pathinfo   =   pathinfo($this->full_file_path);
                    if(!isset($pathinfo['extension'])   ||  !in_array(strtolower($pathinfo['extension']), $this->allowed_file_type))
                        die();
                                            
                    //check if the file is in allowed path
                    $found  =   FALSE;
                    foreach($this->environment->allowed_paths   as  $allowed_path)
                        {
                            $result     =   stripos($this->full_file_path, $allowed_path);
                            if($result  !== FALSE   &&  $result === 0)
                                {
                                    $found  =   TRUE;
                                    break;
                                }
                        }
                    
                    if(! $found )
                        die();
                        
                        
                    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
                        {
                            if  ( function_exists('ob_gzhandler')  && ini_get('zlib.output_compression'))
                                ob_start();    
                                else
                                {
                                    ob_start('ob_gzhandler'); ob_start();
                                }
                        }
                        else
                        {
                            ob_start();
                        }
                    
                    
                }
            
            function __destruct()
                {
                    
                    if(ob_get_level()   <   1)
                        return;
                        
                    $out = ob_get_contents();
                    ob_end_clean();
                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $out;
                }
            
            
            
            /**
            * Load environment
            * 
            */
            function load_environment()
                {
                    $wp_upload_dir              =   wp_upload_dir();
                                                
                    require_once( $wp_upload_dir['basedir'] . '/wph/environment.php' );
                    
                    $this->environment  =   json_decode($environment_variable);
                    
                }
            
            
            /**
            * Define some of WordPress constants which will be used
            * 
            */
            function define_wp_constants()
                {
                    
                    if ( !defined('WP_CONTENT_DIR') )
                        define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );   
                    
                    
                    if ( ! defined( 'WP_CONTENT_URL' ) ) {
                            define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' ); // full url - WP_CONTENT_DIR is defined further up
                        } 
                    
                }
            
            
            /**
            * Process the action
            *     
            */
            function run()
                {

                    switch($this->action)
                        {
                            case 'style-clean'  :   
                                                    $this->style_clean();
                                                    break;
                            
                        }
                        
                }
                
            
            /**
            * Clean the file
            *     
            */
            function style_clean()
                {
                    //output headers
                    $expires_offset = 31536000;                    
                    
                    header('Content-Type: text/css; charset=UTF-8');
                    header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
                    header("Cache-Control: public, max-age=$expires_offset");
                    header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($this->full_file_path)).' GMT', true);
                    
                    $handle         = fopen($this->full_file_path, "r");
                    $file_data      = fread($handle, filesize($this->full_file_path));
                    fclose($handle);
                    
                    $file_data  =   preg_replace('!/\*.*?\*/!s', '', $file_data);
                    $file_data  =   preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $file_data);
       
                             
                    if(isset($this->environment->theme))
                        {
                            $var_theme  =   $this->environment->theme;
                            if( $var_theme->folder_name !=  ''  &&  $var_theme->mapped_name !=  '' )
                                $file_data  =   str_replace('../' . $var_theme->folder_name .'/', '../' . $var_theme->mapped_name .'/', $file_data);   
                        }
                    if(isset($this->environment->child_theme))
                        {
                            $var_theme  =   $this->environment->child_theme;
                            if( $var_theme->folder_name !=  ''  &&  $var_theme->mapped_name !=  '' )
                                $file_data  =   str_replace('../' . $var_theme->folder_name .'/', '../' . $var_theme->mapped_name .'/', $file_data);   
                        }
                    
                    $this->push_file_to_cache( $file_data );
                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $file_data;
                    
                }
                
                
            
            /**
            * Save the file to cache
            *     
            * @param mixed $file_data
            */
            function push_file_to_cache( $file_data )
                {
                    
                    $file_url   =   $_SERVER['SERVER_NAME'];
                    $file_url   .=  !empty($this->environment->site_relative_path)    ?   $this->environment->site_relative_path    :   '';   
                    $file_url   .=  $this->replacement_path;
                    
                    $cached_file_path   =   $this->environment->cache_path . $file_url;
                       
                    $pathinfo   =   pathinfo( $cached_file_path );
                    
                    //allow only css files
                    if ( ! in_array ( strtolower ( $pathinfo['extension'] ), array ( 'css') ) )
                        die();
                                
                    if ( ! is_dir( $pathinfo['dirname'] ) ) 
                        {
                           wp_mkdir_p( $pathinfo['dirname'] );
                        }
                
                    //Ensure the realpath is in the cache folder
                    $real_file_path =   realpath ( trailingslashit($pathinfo['dirname']) );
                    $real_file_path =   $this->normalize_path( $real_file_path );
                    if ( ! $real_file_path ||   stripos ( $real_file_path, $this->environment->cache_path ) !== 0 )
                        die();
                
                    global $wp_filesystem;

                    if ( ! is_object ( $wp_filesystem ) ) 
                        {
                            
                            require_once (ABSPATH . '/wp-includes/l10n.php');
                            require_once (ABSPATH . '/wp-includes/formatting.php');
                            require_once (ABSPATH . '/wp-admin/includes/file.php');
                            WP_Filesystem();
                        }
                
                    if( ! $wp_filesystem->put_contents( trailingslashit($pathinfo['dirname']) . $pathinfo['basename'], $file_data , FS_CHMOD_FILE) ) 
                        {
                            //error saving the cache data to cache file
                        }
                
                }
                
            
            /**
            * Normalize the path
            * 
            * @param mixed $path
            */
            function normalize_path( $path )
                {
                    $path   =   str_replace( '\\', '/', $path );
                    return $path;
                }
       
        }

?>
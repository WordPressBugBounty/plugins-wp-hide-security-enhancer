<?php

    /**
    * Compatibility: FlyingPress
    * Introduced at: 5.0.7
    * Last check: 5.0.7
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_flying_press
        {
                        
            var $wph;
                           
            function __construct()
                {
                    if( !   $this->is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                        
                    add_filter( 'flying_press_optimization:after', array( $this , 'flying_press_optimization__after' ), 99 );

                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'flying-press/flying-press.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            function flying_press_optimization__after( $buffer )
                {
                    
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                        
                }
   
        }
        
    new WPH_conflict_handle_flying_press();


?>
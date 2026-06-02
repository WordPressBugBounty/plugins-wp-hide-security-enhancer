<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_vulnerabilities    extends WPH_security_scan_item
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
                    return 'vulnerabilities';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                    'title'         =>  'AI-Powered Vulnerability Monitoring',
                                    'icon'          =>  'dashicons-wordpress-alt',
                                    
                                    'help'          =>  __("<b>Vulnerability Detection & Plugin Security Monitoring</b>

Powered by insights from our proprietary AI engine, trained on advanced models and enriched with CVE records and other trusted vulnerability intelligence sources, this feature continuously monitors your WordPress plugins for known security risks.

It analyzes installed components against a comprehensive vulnerability database to identify:

Known vulnerabilities and exposed weaknesses affecting installed components
Potential exploit vectors that attackers could target

By detecting issues early, the system helps you take proactive action to reduce your attack surface, strengthen site security, and maintain a stable, trustworthy WordPress environment for your users and data.

Why it matters

Early vulnerability detection minimizes risk, improves platform resilience, and helps ensure your website remains secure, reliable, and up to date.",    'wp-hide-security-enhancer'),
                                    
                                    'score_points'  =>  200,
                                    
                                    'callback'      =>  array ( $this, 'scan' ),
                                    'use_transient' =>  TRUE
                                    );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();

                    $_JSON_response['status']       =   FALSE;
                    $_JSON_response['description']  =   '<div class="vulnerability-report vulnerability-sample">

                                                            <div class="description"><span class="dashicons dashicons-no"></span> ' . __( 'Available in WP Hide PRO', 'wp-hide-security-enhancer' ) .
                                                            '<br /><img src="' . WPH_URL .'/assets/images/wp-hide-vulnerability-report-sample.png" alt="" /></div>
                                                            </div>';
                    $_JSON_response['actions']      =   array (
                                                                'fix'       =>  '<a class="button-primary wph-pro" target="_blank" href="https://wp-hide.com/vulnerability-scan-with-ai-know-your-plugins-themes-risk-before-it-becomes-a-problem/">PRO</a>',
                                                                'ignore'            =>  '//--post-generated--',
                                                                'restore'           =>  '//--post-generated--',
                                                                );

              
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>
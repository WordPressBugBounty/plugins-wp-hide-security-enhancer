<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
        <li>
            <p><span class="dashicons dashicons-flag error"></span> <?php esc_html_e( "Your site runs on a server type which the current free plugin version can't create the required rewrite data. Please check with", 'wp-hide-security-enhancer' ) ?> <span class="wph-pro">PRO</span> <?php esc_html_e( "version at", 'wp-hide-security-enhancer' ) ?> <a target="_blank" href="https://wp-hide.com/wp-hide-pro-now-available/">WP-Hide PRO</a>
            <br /><?php esc_html_e( "This basic version can work with Apache, LiteSpeed, IIS, Nginx set as reverse proxy for Apache, your site runs", 'wp-hide-security-enhancer' ) ?> <b><?php echo esc_html ( $_SERVER['SERVER_SOFTWARE'] )  ?></b></p>
        </li>
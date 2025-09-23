<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
        <li>
        <p><span class="dashicons dashicons-flag error"></span> <?php esc_html_e( "Unable to create cache folder at ", 'wp-hide-security-enhancer' ) ?><?php echo esc_html ( WPH_CACHE_PATH ) ?><?php esc_html_e( " Is the folder writable? No cache data will be available.", 'wp-hide-security-enhancer' ) ?></p>
        </li>
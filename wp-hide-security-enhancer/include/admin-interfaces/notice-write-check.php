<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

    <li><p>
        <span class="dashicons dashicons-flag error critical"></span><b><?php esc_html_e("Unable to write/update required rewrite rules to your site", 'wp-hide-security-enhancer') ?> <?php echo esc_html ( $rewrite_file_type ) ?></b>. <?php esc_html_e('Is this file writable? Until fixed, no changes are applied on the front side.', 'wp-hide-security-enhancer') ?>
        <br /><?php esc_html_e("Try to go at Settings > Permalinks and save once, the core will attempt to update the required rewrites. If the problem persists, check with your host support on the correct file write permission.", 'wp-hide-security-enhancer') ?>
        <br /><?php esc_html_e("Once the file permission is fixed, Save the options once.", 'wp-hide-security-enhancer') ?>
    </p>
    </li>
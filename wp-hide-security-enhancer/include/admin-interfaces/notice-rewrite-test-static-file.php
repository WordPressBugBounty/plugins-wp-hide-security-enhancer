<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
        
        $allow_tags =   WPH_functions::get_general_description_allowed_tags();
?>

        <li>
            <p><span class="dashicons dashicons-flag error"></span><b><?php esc_html_e("Rewrite test for static php files failed! ", 'wp-hide-security-enhancer') ?></b> 
            <?php echo wp_kses ( $result, $allow_tags ) ?>
            <br /><?php esc_html_e("This is a <b>soft error</b> and impact only the option at Rewrite > Theme > 'Remove description header from Style file' and should be disabled until the issue fixed.", 'wp-hide-security-enhancer') ?>
            </p>
        </li>
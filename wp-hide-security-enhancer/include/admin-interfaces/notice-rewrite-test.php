<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?><li>
<?php

    if ( $result === FALSE )
        {
        ?>
        <p><span class="dashicons dashicons-flag error critical"></span><b><?php esc_html_e("Rewrite test failed! ", 'wp-hide-security-enhancer') ?></b> <?php esc_html_e("Ensure the rewrites are active for your server.", 'wp-hide-security-enhancer') ?>.</p>
        <?php
        }
        else
        {
        ?>
        <p><span class="dashicons dashicons-flag error critical"></span><b><?php esc_html_e("Rewrite test failed! ", 'wp-hide-security-enhancer') ?></b> <?php echo esc_html ( $result ) ?></p>
        <?php   
        }
        
        
?>
</li>
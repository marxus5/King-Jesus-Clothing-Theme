<?php
/**
 * WooCommerce Template
 * This overrides WooCommerce's default layout
 */

get_header(); ?>

<div class="woocommerce-container" style="max-width: 1200px; margin: 3rem auto; padding: 0 2rem;">
    <?php woocommerce_content(); ?>
</div>

<?php get_footer(); ?> 
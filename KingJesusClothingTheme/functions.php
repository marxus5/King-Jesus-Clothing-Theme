<?php
function kjc_enqueue_qty_script(){
    wp_enqueue_script('kjc-qty-buttons', get_stylesheet_directory_uri() . '/js/qty-buttons.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'kjc_enqueue_qty_script');

function printshop_enqueue_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'printshop_enqueue_styles');

// Add WooCommerce support
function printshop_add_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'printshop_add_woocommerce_support');

// Register menu
register_nav_menus(array(
    'primary' => 'Primary Menu'
));




?>


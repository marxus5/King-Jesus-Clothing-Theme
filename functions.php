<?php
wp_enqueue_style(
    'main-style',
    get_stylesheet_uri(),
    [],
    filemtime(get_stylesheet_directory() . '/style.css')
);

// wp_enqueue_script(
//     'main-js',
//     get_template_directory_uri() . '/js/main.js',
//     [],
//     filemtime(get_template_directory() . '/js/main.js'),
//     true
// );

function printshop_enqueue_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'printshop_enqueue_styles');

// Add WooCommerce support
function printshop_add_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'printshop_add_woocommerce_support');

// Register menu
function printshop_register_menus() {
    register_nav_menus(array(
        'primary' => 'Primary Menu'
    ));
}
add_action('after_setup_theme', 'printshop_register_menus');

// Declare WooCommerce support (important!)
function printshop_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));
}
add_action('after_setup_theme', 'printshop_woocommerce_setup');
?>
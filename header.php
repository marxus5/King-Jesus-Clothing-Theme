<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header>
        <nav>
            <a class="logo" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
            <ul class="nav-links">
                <li><a href="<?php echo home_url(); ?>">Home</a></li>
                <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
                <li><a href="<?php echo home_url('/blog'); ?>">Blog</a></li>
                <li><a href="<?php echo home_url('/about-us'); ?>">About Us</a></li>
                <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                <li class="nav-cart">
                    <?php
                        $checkout_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart');
                        $cart_count = 0;
                        if ( function_exists('WC') ) {
                            $cart = WC()->cart;
                            if ( $cart ) {
                                $cart_count = $cart->get_cart_contents_count();
                            }
                        }
                    ?>
                    <a href="<?php echo esc_url( $checkout_url ); ?>" title="Checkout">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <?php if ( $cart_count > 0 ) : ?>
                            <span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </header> 
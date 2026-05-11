<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <style>
        /* Reset body margin */
    body {
        margin: 0;
        padding: 0;
    }

    /* Header */
    header {
        background: #000000;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    nav {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        color: #ffffff;
        text-decoration: italic;
        z-index: 0;
    }

    .nav-links {
        display: flex;
        list-style: none;
        gap: 2rem;
        margin: 0;
        padding: 0;
    }

    .nav-links a {
        text-decoration: none;
        color: #ffffff;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-links a:hover {
        color: #9b9b9b;
    }

    /* Hamburger Menu */
    .hamburger {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        z-index: 101;
    }

    .hamburger span {
        width: 28px;
        height: 3px;
        background: #ffffff;
        border-radius: 2px;
        transition: all 0.3s;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(7px, 4.5px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -4.5px);
    }

    /* Mobile Menu Styles */
    @media (max-width: 768px) {
        nav {
            padding: 1rem 1.5rem;
        }

        .hamburger {
            display: flex;
        }

        .nav-links {
            position: fixed;
            top: 0;
            right: -100%;
            height: 100dvh;
            height: 100vh;
            width: 70%;
            max-width: 300px;
            background: rgb(39, 39, 39);
            flex-direction: column;
            padding: 5rem 2rem 2rem;
            gap: 1.5rem;
            box-shadow: -2px 0 10px rgba(255, 255, 255, 0);
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .nav-links.active {
            right: 0;
        }

        .nav-links a {
            font-size: 1.125rem;
            padding: 0.5rem 0;
        }

        /* Overlay */
        .nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100dvh;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 99;
        }

        .nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }
    }

    /* Extra small screens */
    @media (max-width: 570px) {
        .logo {
            font-size: 1.25rem;
        }

        nav {
            padding: 1rem;
        }

        .nav-links {
            width: 80%;
        }
    }

    @media (max-width: 320px) {
        .logo {
            font-size: 1.125rem;
        }

        .nav-links {
            width: 85%;
            padding: 4rem 1.5rem 1.5rem;
        }
    }
    </style>

    <!-- Free Shipping Banner -->
    <div class="shipping-ticker-banner">
        <span class="shipping-ticker-text">Free shipping on orders +$80</span>
    </div>

    <header>
        <nav>
            <a class="logo" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>

            <!-- Hamburger Menu Button -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links" id="navLinks">
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
    
    <!-- Navigation Overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    const navOverlay = document.getElementById('navOverlay');

    function toggleMenu() {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
        navOverlay.classList.toggle('active');
        
        // Prevent body scroll when menu is open
        if (navLinks.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Toggle menu on hamburger click
    hamburger.addEventListener('click', toggleMenu);

    // Close menu when overlay is clicked
    navOverlay.addEventListener('click', toggleMenu);

    // Close menu when a link is clicked
    const menuLinks = navLinks.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleMenu();
            }
        });
    });

    // Close menu on window resize if open
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && navLinks.classList.contains('active')) {
            toggleMenu();
        }
    });
});
</script>
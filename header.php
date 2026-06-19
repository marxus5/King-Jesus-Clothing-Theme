<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- Global chrome + homepage styles now live in css/main.css (enqueued in functions.php) -->

    <!-- FIXED TOP BANNER -->
    <div class="top-banner">✈ Free International Shipping on orders $80+</div>

    <!-- STICKY NAVBAR (just below banner) -->
    <nav class="navbar at-top" id="navbar">
        <div class="nav-left">
            <a href="<?php echo esc_url( home_url('/shop') ); ?>">Shop</a>
            <a href="<?php echo esc_url( home_url('/blog') ); ?>">Blog</a>
            <a href="<?php echo esc_url( home_url('/about-us') ); ?>">About</a>
            <a href="<?php echo esc_url( home_url('/contact') ); ?>">Contact</a>
        </div>
        <div class="nav-center">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/king-jesus-clothing-logo.png"
                alt="King Jesus Clothing"
                class="nav-logo">
            <a href="<?php echo esc_url( home_url() ); ?>" class="nav-brand"><?php bloginfo('name'); ?></a>
        </div>
        <div class="nav-right">
            <div class="cart-wrap">
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
                <a href="<?php echo esc_url( $checkout_url ); ?>" class="cart-link">
                    <svg class="cart-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <?php if ( $cart_count > 0 ) : ?>
                        <span class="cart-badge"><?php echo esc_html( $cart_count ); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <div class="hamburger" onclick="toggleMenu()"><span></span><span></span><span></span></div>
        </div>
    </nav>

    <!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
        <span class="mobile-close" onclick="toggleMenu()">✕</span>
        <a href="<?php echo esc_url( home_url('/shop') ); ?>" onclick="toggleMenu()">Shop</a>
        <a href="<?php echo esc_url( home_url('/blog') ); ?>" onclick="toggleMenu()">Blog</a>
        <a href="<?php echo esc_url( home_url('/about-us') ); ?>" onclick="toggleMenu()">About</a>
        <a href="<?php echo esc_url( home_url('/contact') ); ?>" onclick="toggleMenu()">Contact</a>
    </div>

    <script>

        // Define modal functions early so they're available when elements load
      const GOOGLE_SHEET_URL = 'https://script.google.com/macros/s/AKfycbz-uCZoMRAk0Y3asGnqiwpu3CxRy0PzIvg30eZzT6OfVaJH_VTEk7sPvnZKnQ6_r-Ba/exec';
      const MODAL_STORAGE_KEY = 'kj-modal-shown-session';

      function isCheckoutPage() {
        return window.location.pathname.includes('/checkout') || window.location.pathname.includes('/cart');
      }

      function initModalSystem() {

        const promo = document.getElementById('stickyPromo');
        const modal = document.getElementById('modalOverlay');


        if (promo) {
          promo.classList.add('show');
        }

        if (!sessionStorage.getItem(MODAL_STORAGE_KEY) && modal) {
          setTimeout(() => {
            modal.classList.add('open');
            sessionStorage.setItem(MODAL_STORAGE_KEY, 'true');
          }, 10000);
        }
      }

      // Try to init immediately, or wait for DOM
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initModalSystem, 100);
      } else {
        document.addEventListener('DOMContentLoaded', () => setTimeout(initModalSystem, 100));
      }
      // Modal action functions (globally accessible for onclick handlers)
      async function sendToGoogleSheet(name, email, source) {
        try {
          await fetch(GOOGLE_SHEET_URL, {
            method: 'POST',
            mode: 'no-cors',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, source, timestamp: new Date().toISOString() })
          });
        } catch(err) {
          console.warn('Sheet submit error:', err);
        }
      }

      function openModal() {
        // if (!isCheckoutPage()) {
        if(true) {
          const modal = document.getElementById('modalOverlay');
          if (modal) {
            modal.classList.add('open');
            sessionStorage.setItem(MODAL_STORAGE_KEY, 'true');
          }
        }
      }

      function closeModal() {
        const modal = document.getElementById('modalOverlay');
        if (modal) modal.classList.remove('open');
        const promo = document.getElementById('stickyPromo');
        if (promo) setTimeout(() => promo.classList.add('show'), 600);
      }

      async function submitModal() {
        const name = document.getElementById('modalName').value.trim();
        const email = document.getElementById('modalEmail').value.trim();
        if (!name || !email) {
          alert('Please fill in both fields.');
          return;
        }
        await sendToGoogleSheet(name, email, 'popup-modal');
        document.getElementById('modalForm').innerHTML =
          '<div style="padding:20px 0;font-size:17px;color:#1D1D1D;line-height:1.6">🙏 Thank you, ' + name + '!<br><br>Your 15% discount is <strong style="color:#CE202F;">JesusIsKing15</strong></div>';
        // setTimeout(closeModal, 4000);
      }    

    // Global toggle menu function for onclick handlers
    function toggleMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            mobileMenu.classList.toggle('open');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const mobileMenu = document.getElementById('mobileMenu');
        const isHomepage = document.body.classList.contains('page-template-page-homepage');

        
        // Close menu when a link is clicked
        const menuLinks = mobileMenu.querySelectorAll('a:not(.mobile-close)');
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (mobileMenu.classList.contains('open')) {
                    mobileMenu.classList.remove('open');
                }
            });
        });

        // Close menu on window resize if open
        window.addEventListener('resize', function() {
            if (window.innerWidth > 880 && mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
            }
        });

        // Navbar: transparent at the very top on the homepage, solid once scrolled.
        function updateNavbar() {
            if (!isHomepage) {                       // non-homepage pages stay solid
                navbar.classList.remove('at-top');
                navbar.classList.add('scrolled');
                return;
            }
            if (window.scrollY > 20) {
                navbar.classList.remove('at-top');
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.add('at-top');
                navbar.classList.remove('scrolled');
            }
        }

        // Top banner: hide when scrolling down, reveal when scrolling up.
        const bannerH = 38;
        let lastScroll = window.scrollY;
        function updateBanner() {
            const y = window.scrollY;
            if (y > lastScroll && y > bannerH) {
                document.body.classList.add('banner-hidden');    // scrolling down
            } else if (y < lastScroll) {
                document.body.classList.remove('banner-hidden');  // scrolling up
            }
            lastScroll = y;
        }

        updateNavbar();
        window.addEventListener('scroll', function () {
            updateNavbar();
            updateBanner();
        }, { passive: true });
    });
</script>
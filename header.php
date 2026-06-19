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
    <style>
        :root {
            --black: #1D1D1D;
            --red: #CE202F;
            --red-deep: #7A0E1A;
            --brown: #8B5E3C;
            --brown-light: #A0714F;
            --cream: #FAF3EB;
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --white: #FFFFFF;
            --off-white: #FAF3EB;
            --text: #1D1D1D;
            --text-muted: #5A4A3A;
            --font: 'Segoe UI', system-ui, -apple-system, sans-serif;
            --banner-h: 38px;
            --nav-h: 68px;
        }

        /* Reset body margin */
        body {
            margin: 0;
            padding: 0;
        }

        /* Only add top padding on non-v2 pages */
        body:not(.page-template-page-homepage) {
            padding-top: calc(var(--banner-h) + var(--nav-h));
        }

        /* ─── TOP BANNER — always fixed at very top ─── */
        .top-banner {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--banner-h);
            background: var(--red);
            text-align: center;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: #fff;
            z-index: 300;
        }

        /* ─── NAVBAR — sticky just below banner ─── */
        .navbar {
            position: fixed;
            top: var(--banner-h);
            left: 0; right: 0;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5vw;
            height: var(--nav-h);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        }

        /* On homepage: start transparent at top */
        body.page-template-page-homepage .navbar {
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        /* On homepage when at top: keep transparent */
        body.page-template-page-homepage .navbar.at-top {
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        /* On homepage when scrolled: make solid white */
        body.page-template-page-homepage .navbar.scrolled {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        }

        /* Text colors in transparent state (over dark hero) — homepage only */
        body.page-template-page-homepage .navbar.at-top .nav-left a,
        body.page-template-page-homepage .navbar.at-top .cart-icon { color: #fff; }
        body.page-template-page-homepage .navbar.at-top .hamburger span { background: #fff; }

        /* Text colors in solid state */
        body.page-template-page-homepage .navbar.scrolled .nav-left a,
        body.page-template-page-homepage .navbar.scrolled .cart-icon { color: var(--text); }
        body.page-template-page-homepage .navbar.scrolled .hamburger span { background: var(--text); }

        /* On non-homepage pages: navbar is always solid with dark text */
        body:not(.page-template-page-homepage) .navbar .nav-left a,
        body:not(.page-template-page-homepage) .navbar .cart-icon { color: var(--text); }
        body:not(.page-template-page-homepage) .navbar .hamburger span { background: var(--text); }

        .nav-left { display: flex; gap: 28px; align-items: center; }
        .nav-left a {
            text-decoration: none; font-size: 13px; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase; transition: color 0.2s;
        }
        .nav-left a:hover { color: var(--red) !important; }
        .nav-left a::after {
            content: '';
            display: block;
            height: 2px;
            background: var(--gold);
            width: 0;
            transition: width 0.25s ease;
            margin-top: 2px;
        }
        .nav-left a:hover::after { width: 100%; }


        .nav-right { display: flex; align-items: center; gap: 20px; margin-left: auto; }
        .cart-wrap { position: relative; cursor: pointer; }
        .cart-icon { width: 22px; height: 22px; display: block; transition: color 0.3s; }
        .cart-badge {
            position: absolute; top: -7px; right: -8px;
            background: var(--red); color: #fff; font-size: 10px; font-weight: 700;
            width: 17px; height: 17px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
        .hamburger span { display: block; width: 22px; height: 2px; transition: 0.3s; }

        .cart-link {
            text-decoration: none;
            color: inherit;
        }

        /* ─── MODAL ─── */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; visibility: hidden; transition: opacity 0.35s, visibility 0.35s; }
        .modal-overlay.open { opacity: 1; pointer-events: auto; visibility: visible; }
        .modal { background: #fff; border: 1px solid #eee; max-width: 480px; width: 100%; padding: 48px 36px 40px; position: relative; transform: translateY(30px) scale(0.96); transition: transform 0.35s; text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,0.12); }
        .modal-overlay.open .modal { transform: translateY(0) scale(1); }
        .modal-close { position: absolute; top: 16px; right: 20px; background: none; border: none; color: #aaa; font-size: 22px; cursor: pointer; transition: color 0.2s; line-height: 1; z-index: 1001; pointer-events: auto; }
        .modal-close:hover { color: var(--text); }
        .modal-badge { display: inline-block; background: var(--red); color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; padding: 6px 14px; margin-bottom: 18px; border-bottom: 2px solid var(--gold); }
        .modal h2 { font-size: clamp(22px, 5vw, 30px); font-weight: 800; margin-bottom: 10px; line-height: 1.2; color: var(--text); }
        .modal p { font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 28px; }
        .modal-form { display: flex; flex-direction: column; gap: 12px; }
        .modal-input { background: var(--off-white); border: 1px solid #e0e0e0; color: var(--text); padding: 14px 16px; font-family: var(--font); font-size: 14px; outline: none; transition: border-color 0.2s; width: 100%; pointer-events: auto; }
        .modal-input:focus { border-color: var(--red); }
        .modal-input::placeholder { color: #aaa; }
        .modal-submit { background: var(--red); color: #fff; border: none; padding: 16px; font-family: var(--font); font-size: 13px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; cursor: pointer; transition: opacity 0.2s, transform 0.2s; margin-top: 4px; pointer-events: auto; }
        .modal-submit:hover { opacity: 0.88; transform: translateY(-1px); }
        .modal-skip { font-size: 12px; color: #aaa; margin-top: 14px; cursor: pointer; transition: color 0.2s; pointer-events: auto; }
        .modal-skip:hover { color: var(--text); text-decoration: underline; }

        /* ─── STICKY 15% TRIANGLE ─── */
        .sticky-promo { position: fixed; bottom: 0; left: 0; z-index: 500; cursor: pointer; opacity: 0; pointer-events: none; transition: opacity 0.3s, transform 0.3s; transform: translateX(-6px); }
        .sticky-promo.show { opacity: 1; pointer-events: auto; transform: translateX(0); }
        .sticky-triangle { width: 0; height: 0; border-top: 56px solid transparent; border-bottom: 56px solid transparent; border-left: 84px solid var(--red); position: relative; pointer-events: auto; }
        .sticky-triangle-text { position: absolute; top: 50%; left: -78px; transform: translateY(-50%); width: 66px; text-align: center; color: #fff; font-size: 11px; font-weight: 800; letter-spacing: 0.04em; line-height: 1.3; pointer-events: none; }
    </style>

    
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

        navbar.classList.add('scrolled');

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
        updateNavbar();
        window.addEventListener('scroll', updateNavbar, { passive: true });
    });
</script>
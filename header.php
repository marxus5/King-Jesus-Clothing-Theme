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
      // Config for the email popup + checkout opt-in (AJAX endpoint + nonce).
      window.kjcData = {
        ajaxUrl: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
        nonce: '<?php echo esc_js( wp_create_nonce( 'kjc_coupon' ) ); ?>'
      };

        // Define modal functions early so they're available when elements load
      const GOOGLE_SHEET_URL = 'https://script.google.com/macros/s/AKfycbz-uCZoMRAk0Y3asGnqiwpu3CxRy0PzIvg30eZzT6OfVaJH_VTEk7sPvnZKnQ6_r-Ba/exec';
      const MODAL_STORAGE_KEY = 'kj-modal-shown-session';
      const STICKY_DISMISSED_KEY = 'kj-sticky-dismissed';

      function isCheckoutPage() {
        return window.location.pathname.includes('/checkout') || window.location.pathname.includes('/cart');
      }

      function initModalSystem() {
        const modal = document.getElementById('modalOverlay');

        if (sessionStorage.getItem(MODAL_STORAGE_KEY)) {
          // Popup already appeared earlier this session — the sticky tab can
          // show right away (unless the shopper dismissed it).
          showSticky();
        } else if (modal) {
          // First view this session: reveal the popup after a delay, and only
          // then bring in the sticky 15% tab.
          setTimeout(() => {
            modal.classList.add('open');
            sessionStorage.setItem(MODAL_STORAGE_KEY, 'true');
            showSticky();
          }, 10000);
        }
      }

      // Reveal the sticky 15% corner tab, unless dismissed this session.
      function showSticky() {
        try { if (sessionStorage.getItem(STICKY_DISMISSED_KEY)) return; } catch (e) {}
        const promo = document.getElementById('stickyPromo');
        if (promo) promo.classList.add('show');
      }

      // Hide the sticky tab and remember the choice for this session.
      function dismissSticky(e) {
        if (e) { e.stopPropagation(); e.preventDefault(); }
        const promo = document.getElementById('stickyPromo');
        if (promo) promo.classList.remove('show');
        try { sessionStorage.setItem(STICKY_DISMISSED_KEY, '1'); } catch (err) {}
      }

      // Try to init immediately, or wait for DOM
      if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initModalSystem, 100);
      } else {
        document.addEventListener('DOMContentLoaded', () => setTimeout(initModalSystem, 100));
      }
      // Modal action functions (globally accessible for onclick handlers)
      async function sendToGoogleSheet(payload) {
        try {
          await fetch(GOOGLE_SHEET_URL, {
            method: 'POST',
            mode: 'no-cors',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(Object.assign({ timestamp: new Date().toISOString() }, payload))
          });
        } catch(err) {
          console.warn('Sheet submit error:', err);
        }
      }

      // Saves the welcome discount to the shopper's cart/session so it
      // auto-applies at checkout — including express (Apple/Google Pay)
      // checkouts, which have no coupon field. Backed by a 30-day cookie so it
      // persists if they leave and come back.
      function rememberWelcomeCoupon() {
        try {
          document.cookie = 'kjc_pending_coupon=1; path=/; max-age=' + (60 * 60 * 24 * 30) + '; SameSite=Lax';
        } catch (e) {}
        if (window.kjcData && window.kjcData.ajaxUrl) {
          const body = new URLSearchParams({ action: 'kjc_apply_coupon', nonce: window.kjcData.nonce });
          fetch(window.kjcData.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body,
            credentials: 'same-origin'
          }).catch(function () {});
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
        // Bring the sticky tab back (respects a prior dismissal).
        setTimeout(showSticky, 600);
      }

      let kjcModalSubmitting = false;
      async function submitModal() {
        // Guard against double-clicks while the request is in flight — stops
        // duplicate rows piling up in the Google Sheet.
        if (kjcModalSubmitting) return;

        const emailEl = document.getElementById('modalEmail');
        const phoneEl = document.getElementById('modalPhone');
        const btn = document.getElementById('modalSubmitBtn');
        const email = emailEl ? emailEl.value.trim() : '';
        const phone = phoneEl ? phoneEl.value.trim() : '';

        // Email is required; phone is optional.
        const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        if (!emailOk) {
          alert('Please enter a valid email address.');
          if (emailEl) emailEl.focus();
          return;
        }

        kjcModalSubmitting = true;
        if (btn) {
          btn.disabled = true;
          btn.textContent = 'Sending…';
          btn.style.opacity = '0.7';
          btn.style.cursor = 'default';
        }

        await sendToGoogleSheet({ email: email, phone: phone, source: 'popup-modal' });

        // Save the discount to their cart/session so it auto-applies at checkout.
        rememberWelcomeCoupon();

        document.getElementById('modalForm').innerHTML =
          '<div style="padding:20px 0;font-size:17px;color:#1D1D1D;line-height:1.6">🙏 Thank you for joining the family!<br><br>Your 15% discount code is <strong style="color:#CE202F;">JesusIsKing15</strong><br><br><span style="font-size:14px;color:#555;">We\'ve saved it to your cart — it\'ll apply automatically at checkout.</span></div>';
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

        // Scroll handling (navbar transparency + banner hide/show), throttled
        // with requestAnimationFrame so it runs at most once per frame — keeps
        // scrolling smooth on mobile.
        const bannerH = 38;
        // Don't hide the banner until the shopper has scrolled down a fair bit,
        // and only react to deliberate scrolls (not tiny jitters) so the layout
        // doesn't keep resizing on mobile.
        const HIDE_AFTER = 160;   // px scrolled before the banner may hide
        const SCROLL_DELTA = 12;  // ignore movements smaller than this
        let lastScroll = window.scrollY;
        let ticking = false;

        // Non-homepage navbar is always solid; set once.
        if (!isHomepage) {
            navbar.classList.add('scrolled');
            navbar.classList.remove('at-top');
        }

        function applyScrollState() {
            const y = window.scrollY;

            // Navbar transparent at very top (homepage only), solid once scrolled.
            if (isHomepage) {
                const solid = y > 20;
                navbar.classList.toggle('scrolled', solid);
                navbar.classList.toggle('at-top', !solid);
            }

            // Always show the banner near the very top.
            if (y <= bannerH) {
                document.body.classList.remove('banner-hidden');
                lastScroll = y;
                ticking = false;
                return;
            }

            // Otherwise only flip state on a deliberate scroll (>= SCROLL_DELTA),
            // and only hide once we're past HIDE_AFTER. This stops the constant
            // resize from small up/down movements.
            const diff = y - lastScroll;
            if (Math.abs(diff) >= SCROLL_DELTA) {
                if (diff > 0 && y > HIDE_AFTER) {
                    document.body.classList.add('banner-hidden');   // scrolling down
                } else if (diff < 0) {
                    document.body.classList.remove('banner-hidden'); // scrolling up
                }
                lastScroll = y;
            }
            ticking = false;
        }

        applyScrollState();
        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(applyScrollState);
                ticking = true;
            }
        }, { passive: true });
    });
</script>
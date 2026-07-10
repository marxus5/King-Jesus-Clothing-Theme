
    <footer class="footer">

    <!-- 15% OFF EMAIL SIGN-UP (uses the same welcome-coupon flow as the popup) -->
    <div class="footer-signup">
      <div class="footer-signup-text">
        <h4>Get <span>15% Off</span> Your First Order</h4>
        <p>Join the King Jesus Clothing family for a 15% welcome discount, early access to new drops, and updates.</p>
      </div>
      <form class="footer-signup-form" id="footerSignupForm" onsubmit="return submitFooterSignup(event)">
        <input type="email" id="footerSignupEmail" class="footer-signup-input" placeholder="Your email address" autocomplete="email" required>
        <button type="submit" class="footer-signup-btn" id="footerSignupBtn">Claim 15% Off</button>
      </form>
      <p class="footer-signup-msg" id="footerSignupMsg" hidden></p>
    </div>

    <div class="footer-grid">
      <div>
        <div class="footer-brand">King Jesus Clothing</div>
        <div class="footer-tagline">Clothing that declares faith, funds missions, and glorifies the King of Kings.</div>
      </div>
      <div class="footer-col">
        <h4>Shop</h4>
        <a href="<?php echo home_url('/shop'); ?>">Shop</a>
        <a href="<?php echo home_url('/about-us'); ?>">About</a>
        <a href="<?php echo home_url('/returns-refunds-policy'); ?>">Returns & Refunds</a>
        <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
       
      </div>
      <div class="footer-col">
        <h4><a href="<?php echo home_url('/contact'); ?>">Contact & Socials</a></h4>
        <div class="social-icons"> 
            <a href="https://facebook.com/kingjesusclothingbrand" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com/kingjesusclothingbrand" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.tiktok.com/@kingjesusclothing" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-tiktok"></i>
            </a>
        </div>
      </div>
    </div>
        <div class="footer-bottom">
            <div class="footer-copy">© 2026 King Jesus Clothing. All rights reserved.</div>
            <div class="footer-copy">Privacy Policy &nbsp;·&nbsp; Terms of Service</div>
        </div>
    </footer>

    <!-- EMAIL MODAL — shown on all pages except checkout -->
    <div class="modal-overlay" id="modalOverlay">
      <div class="modal">
        <button class="modal-close" onclick="closeModal()">✕</button>
        <div class="modal-badge">Exclusive Offer</div>
        <h2>Get 15% Off<br>Your First Order</h2>
        <p>Join the King Jesus Clothing Family and receive a 15% discount, early access to new drops, and updates.</p>
        <div class="modal-form" id="modalForm">
          <input class="modal-input" id="modalEmail" type="email" placeholder="Your email address" required>
          <input class="modal-input" id="modalPhone" type="tel" placeholder="Phone number (optional)" autocomplete="tel">
          <button class="modal-submit" id="modalSubmitBtn" onclick="submitModal()">Claim My 15% Off</button>
          <div class="modal-skip" onclick="closeModal()">No thanks, I'll pay full price</div>
        </div>
      </div>
    </div>

    <!-- STICKY 15% FOLDED CORNER — bottom-left; revealed after the popup shows -->
    <div class="sticky-promo" id="stickyPromo" onclick="openModal()" role="button" tabindex="0" aria-label="Get 15% off your first order">
      <div class="sticky-fold"></div>
      <span class="sticky-fold-text">15%<br>OFF</span>
      <button type="button" class="sticky-dismiss" onclick="dismissSticky(event)" aria-label="Dismiss 15% off tab">&times;</button>
    </div>

    <script>
      // Modal/sticky functions live in header.php (global scope). The footer
      // sign-up reuses the same Google-Sheet + welcome-coupon helpers.
      function submitFooterSignup(e) {
        if (e) { e.preventDefault(); }
        var input = document.getElementById('footerSignupEmail');
        var btn   = document.getElementById('footerSignupBtn');
        var msg   = document.getElementById('footerSignupMsg');
        var email = input ? input.value.trim() : '';

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          if (msg) { msg.hidden = false; msg.style.color = '#ffb3b3'; msg.textContent = 'Please enter a valid email address.'; }
          if (input) { input.focus(); }
          return false;
        }

        if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

        // Same flow as the popup: log the email and stash the welcome coupon so
        // it auto-applies at checkout (including express/Apple Pay).
        if (typeof sendToGoogleSheet === 'function') {
          sendToGoogleSheet({ email: email, source: 'footer-signup' });
        }
        if (typeof rememberWelcomeCoupon === 'function') {
          rememberWelcomeCoupon();
        }

        if (msg) {
          msg.hidden = false;
          msg.style.color = '';
          msg.innerHTML = '🙏 You\'re in! Code <strong>JesusIsKing15</strong> is saved to your cart — it applies at checkout.';
        }
        if (input) { input.value = ''; }
        if (btn) { btn.textContent = 'Claimed ✓'; }
        return false;
      }
    </script>

    <?php wp_footer();?>
</body>
</html> 

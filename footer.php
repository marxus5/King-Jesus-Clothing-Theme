
    <footer class="footer">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">King Jesus Clothing</div>
        <div class="footer-tagline">Clothing that declares faith, funds missions, and glorifies the King of Kings.</div>
      </div>
      <div class="footer-col">
        <h4>Shop</h4>
        <a href="<?php echo home_url('/shop'); ?>">Shop</a>
        <a href="<?php echo home_url('/about'); ?>">About</a>
        <a href="<?php echo home_url('/returns-refunds-policy'); ?>">Returns & Refunds</a>
        <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
       
      </div>
      <div class="footer-col">
        <h4><a href="<?php echo home_url('/contact'); ?>">Contact & Socials</a></h4>
        <div class="social-icons"> 
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-tiktok"></i>
            </a>
        </div>
      </div>
    </div>
        <div class="footer-bottom">
            <div class="footer-copy">© 2025 King Jesus Clothing. All rights reserved.</div>
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
          <input class="modal-input" id="modalName" type="text" placeholder="Your first name">
          <input class="modal-input" id="modalEmail" type="email" placeholder="Your email address">
          <button class="modal-submit" onclick="submitModal()">Claim My 15% Off</button>
          <div class="modal-skip" onclick="closeModal()">No thanks, I'll pay full price</div>
        </div>
      </div>
    </div>

    <!-- STICKY 15% TRIANGLE — bottom left corner (on all pages except checkout) -->
    <div class="sticky-promo" id="stickyPromo" onclick="openModal()">
      <div class="sticky-triangle"><div class="sticky-triangle-text">15%<br>OFF</div></div>
    </div>

    <script>
      // Note: All modal functions are defined in header.php for global scope and early availability
    </script>

    <?php wp_footer();?>
</body>
</html> 
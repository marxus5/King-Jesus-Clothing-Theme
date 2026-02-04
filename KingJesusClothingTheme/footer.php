<footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>King Jesus Clothing</h3>
                <p>Where the Kingdom meets clothing.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                    <li><a href="<?php echo home_url('/returns-refunds-policy'); ?>">Returns & Refunds</a></li>
                    <li><a href="<?php echo home_url('/private-policy'); ?>">Private Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>contact@kingjesus-clothing.org</li>
                </ul>
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
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
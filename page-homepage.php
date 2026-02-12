<?php
/**
 * Template Name: Homepage
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <div class="hero-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/king-jesus-clothing-logo.png" alt="<?php bloginfo('name'); ?>">

        </div>
        <h1 class="hero-title"><?php bloginfo('name'); ?></h1>
        <p class="hero-slogan">Where the kingdom, meets clothing. PLEASE WRK</p>
    </div>
</section>

<!-- Featured Products Carousel -->
<section class="featured-carousel-section">
    <h2 class="section-heading">Featured Collection</h2>
    <div class="carousel-container">
        <div class="carousel-wrapper" id="productCarousel">
            <?php
            // Display WooCommerce products if available
            if (class_exists('WooCommerce')) {
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 8,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                );
                $products = new WP_Query($args);
                
                if ($products->have_posts()) {
                    while ($products->have_posts()) {
                        $products->the_post();
                        global $product;
                        ?>
                        <a href="<?php echo get_permalink(); ?>" class="carousel-item" style="text-decoration: none; color: inherit;">
                            <div class="carousel-item">
                                <div class="carousel-item-image">
                                    <?php if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium');
                                    } else {
                                        echo '👕';
                                    } ?>
                                </div>
                                <div class="carousel-item-content">
                                    <h3 class="carousel-item-title"><?php the_title(); ?></h3>
                                    <p class="carousel-item-price"><?php echo $product->get_price_html(); ?></p>
                                </div>
                            </div>
                        </a>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Placeholder products if WooCommerce not set up yet
                    for ($i = 1; $i <= 6; $i++) {
                        echo '<div class="carousel-item">
                                <div class="carousel-item-image">👕</div>
                                <div class="carousel-item-content">
                                    <h3 class="carousel-item-title">Product ' . $i . '</h3>
                                    <p class="carousel-item-price">$' . (20 + $i * 5) . '.99</p>
                                </div>
                              </div>';
                    }
                }
            } else {
                // Placeholder if WooCommerce not installed
                for ($i = 1; $i <= 6; $i++) {
                    echo '<div class="carousel-item">
                            <div class="carousel-item-image">👕</div>
                            <div class="carousel-item-content">
                                <h3 class="carousel-item-title">Product ' . $i . '</h3>
                                <p class="carousel-item-price">$' . (20 + $i * 5) . '.99</p>
                            </div>
                          </div>';
                }
            }
            ?>
        </div>
        <div class="carousel-nav">
            <button class="carousel-nav-btn" onclick="scrollCarousel('left')">←</button>
            <button class="carousel-nav-btn" onclick="scrollCarousel('right')">→</button>
        </div>
    </div>
    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="view-all-btn">View All Products</a>
</section>

<!-- Featured Product Section -->
<section class="featured-product-section">
    <div class="featured-product-container">
        <!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/friend-of-god-background.png" alt="Freind of God Background" class="featured-product-image-background"> -->
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/friend-of-god-graphic.png" alt="Friend of God Hoodie Graphic">
        
        <div class="featured-product-content">
            <span class="featured-badge">BESTSELLER</span>
            <h2 class="featured-product-title">FRIEND OF GOD</h2>
            <p class="featured-product-description">
                "For since our friendship with God was restored by the death of his Son while we were still his enemies, we will certainly be saved through the life of his Son. So now we can rejoice in our wonderful new relationship with God because our Lord Jesus Christ has made us friends of God."
                <br>Romans 5:10-11
            </p>
            <!-- <p class="featured-product-description">Now as Christ laid down his life as a demonstration of his love and friendship for us, he calls us to do the same...</p> -->
            <div class="container">
                <!-- <p class="featured-product-price">$50</p> -->
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="featured-product-btn">Shop Now</a>
                <!-- <a href="#" class="featured-product-btn">Message Behind the Merch</a> -->
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="blog-section">
    <h2 class="section-heading">Message Behind the Merch Blog</h2>
    <div class="blog-grid">
        <?php
        $blog_posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 3,
        ));
        
        if ($blog_posts->have_posts()) {
            while ($blog_posts->have_posts()) {
                $blog_posts->the_post();
                ?>
                
                <article class="blog-card">
                    <a href="<?php the_permalink(); ?>" class="blog-post-card" style="text-decoration: none; color: inherit; display: block;">
                    <div class="blog-image">
                        <?php if (has_post_thumbnail()) {
                            the_post_thumbnail('medium');
                        } else {
                            echo '📰';
                        } ?>
                    </div>
                    <div class="blog-content">
                        <p class="blog-date"><?php echo get_the_date('F j, Y'); ?></p>
                        <h3 class="blog-title"><?php the_title(); ?></h3>
                        <p class="blog-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        <a href="<?php the_permalink(); ?>" class="blog-read-more">Read More →</a>
                    </div>
                    </a>
                </article>
                <?php
            }
            wp_reset_postdata();
        } else {
            // Placeholder blog posts
            for ($i = 1; $i <= 3; $i++) {
                echo '<article class="blog-card">
                        <div class="blog-image">📰</div>
                        <div class="blog-content">
                            <p class="blog-date">January ' . (20 + $i) . ', 2026</p>
                            <h3 class="blog-title">Style Guide ' . $i . ': Finding Your Perfect Fit</h3>
                            <p class="blog-excerpt">Discover the latest trends and how to make them work for your unique style. Our experts share their top tips...</p>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                      </article>';
            }
        }
        ?>
    </div>
    <a href="<?php echo home_url('/blog'); ?>" class="view-all-btn">View All Posts</a>
</section>
<!-- Mission Statement Section -->
<section class="mission-cta-section">
    <div class="mission-cta-container">
        <div class="mission-cta-image">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Just-Jesus_Modle-Pic.jpg" alt="King Jesus Clothing Mission" onerror="this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'">
        </div>
        <div class="mission-cta-content">
            <h2>"What did Jesus say?"</h2>
            <p class="mission-tagline">The Heartbeat of King Jesus Clothing</p>
            <p>We exist to focus on, teach, and spread the living words and teachings of Christ and His Kingdom. Our ultimate goal is to proclaim the gospel of the Kingdom, create fresh opportunities for evangelism and discipleship, draw people into deeper intimacy with God, and literally clothe them in Christ through every garment we create.</p>
            <a href="<?php echo home_url('/about'); ?>" class="mission-cta-btn">Learn Our Story</a>
        </div>
    </div>
</section>
<!-- Quote Section -->
<section class="quote-section">
    <div class="quote-container">
        <p class="quote-text">“Above all, clothe yourselves with love, which binds us all together in perfect harmony... Let the message about Christ, in all its richness, fill your lives... And whatever you do or say, do it as a representative of the Lord Jesus...”</p>
        <p class="quote-author">Colossians 3:14-17</p>
    </div>
</section>

<script>
function scrollCarousel(direction) {
    const carousel = document.getElementById('productCarousel');
    const cardWidth = carousel.querySelector('.carousel-item').offsetWidth;
    const gap = 32; // 2rem gap in pixels
    const scrollAmount = cardWidth + gap;
    
    if (direction === 'left') {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}
</script>

<?php get_footer(); ?>
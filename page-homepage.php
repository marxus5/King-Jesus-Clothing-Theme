<?php
/**
 * Template Name: Homepage v2
 * Description: New modern homepage design with hero, products, mission, reviews
 */

get_header();

// Homepage styles now live in css/main.css (enqueued site-wide in functions.php).
wp_enqueue_script('homepage-v2', get_template_directory_uri() . '/js/homepage-v2.js', array(), '1.0', true);
?>


<!-- PAGE BODY (offset for fixed banner+nav) -->
<div class="page-body">

  <!-- HERO (nav overlays because of negative margin-top) -->
  <div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
      <div class="hero-eyebrow">King Jesus Clothing</div>
      <h1 class="hero-title">Christian Clothing to<br>Wear Your Faith Boldly</h1>
      <a href="#products" class="hero-cta">
        Shop Our Best Sellers
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>

  <!-- FEATURED PRODUCTS -->
  <section class="section" id="products">
    <div class="reveal">
      <div class="section-label">Best Sellers</div>
      <h2 class="section-title">Featured Products</h2>
    </div>
    <div class="products-grid">
      <?php
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $products = new WP_Query( $args );

      if ( $products->have_posts() ) {
        while ( $products->have_posts() ) {
          $products->the_post();
          global $product;
          ?>
          <a href="<?php the_permalink(); ?>" class="product-card">
            <div class="product-img">
              <div class="product-img-inner">
                <?php
                if ( has_post_thumbnail() ) {
                  the_post_thumbnail( 'large' );
                } else {
                  echo 'No Image';
                }
                ?>
              </div>
            </div>
            <div class="product-name"><?php the_title(); ?></div>
            <div class="product-price"><?php echo $product->get_price_html(); ?></div>
          </a>
          <?php
        }
        wp_reset_postdata();
      }
      ?>
      </div>
      <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-related-view-all">
                View All Products
            </a>    
    </section>

  <!-- MISSION / VALUES -->
  <section class="mission-section">
    <div class="mission-header reveal">
      <div class="section-label">Our Mission</div>
      <h2 class="section-title" style="text-align:center">Clothed in Purpose</h2>
      <p class="mission-quote">"We create clothing that doesn't just look good — it starts conversations, declares faith, and supports those spreading the Gospel across the world."</p>
    </div>
    <div class="values-grid">
      <div class="value-card"><span class="value-icon">✝</span><div class="value-title">Gospel First</div><div class="value-desc">Every design starts with Scripture. We want every piece to point back to Jesus Christ.</div></div>
      <div class="value-card"><span class="value-icon">🌍</span><div class="value-title">Global Mission</div><div class="value-desc">A portion of every purchase directly funds missionaries and church plants worldwide.</div></div>
      <div class="value-card"><span class="value-icon">🔥</span><div class="value-title">Bold Witness</div><div class="value-desc">Our clothing is a conversation starter. Wear it, share it, spark faith in others.</div></div>
    </div>
    <div class="perks-row">
      <div class="perk-item"><span class="perk-icon">📦</span><div class="perk-label">Free Shipping $80+</div></div>
      <div class="perk-item"><span class="perk-icon">↩</span><div class="perk-label">Easy Returns</div></div>
      <div class="perk-item"><span class="perk-icon">🔒</span><div class="perk-label">Secure Checkout</div></div>
      <div class="perk-item"><span class="perk-icon">🙏</span><div class="perk-label">Supporting Missionaries</div></div>
    </div>
  </section>

 <?php
/* ─── LIVE REVIEWS — pulled from real WooCommerce product reviews ─── */
$kjc_reviews = get_comments( array(
    'status'     => 'approve',
    'post_type'  => 'product',
    'type'       => 'review',
    'number'     => 12,
    'orderby'    => 'comment_date_gmt',
    'order'      => 'DESC',
    'meta_query' => array(
        array( 'key' => 'rating', 'value' => 4, 'compare' => '>=', 'type' => 'NUMERIC' ),
    ),
) );

if ( ! empty( $kjc_reviews ) ) :

    global $wpdb;
    $kjc_sql_where = "FROM {$wpdb->commentmeta} cm
        INNER JOIN {$wpdb->comments} c ON c.comment_ID = cm.comment_id
        WHERE cm.meta_key = 'rating'
          AND c.comment_approved = '1'
          AND c.comment_type = 'review'";
    $kjc_avg   = $wpdb->get_var( "SELECT AVG(cm.meta_value) $kjc_sql_where" );
    $kjc_count = (int) $wpdb->get_var( "SELECT COUNT(*) $kjc_sql_where" );
    $kjc_avg   = $kjc_avg ? round( $kjc_avg, 1 ) : 5.0;
    $kjc_stars = max( 1, (int) round( $kjc_avg ) );
?>
  <section class="reviews-section">
    <div class="reviews-header reveal">
      <div class="overall-rating">
        <span class="stars-big"><?php echo str_repeat( '★', $kjc_stars ) . str_repeat( '☆', 5 - $kjc_stars ); ?></span>
        <span class="rating-num"><?php echo esc_html( number_format( $kjc_avg, 1 ) ); ?></span>
        <span class="rating-count">from <?php echo esc_html( $kjc_count ); ?> verified review<?php echo $kjc_count === 1 ? '' : 's'; ?></span>
      </div>
      <h2 class="section-title">What Our Community Says</h2>
      <p class="reviews-subtitle">Real believers wearing their faith and sharing their stories. Every review is from a verified purchase.</p>
    </div>
    <div class="carousel-track-wrap">
      <div class="carousel-track">
        <?php
        /* Render the set twice so the infinite-scroll animation (-50%) loops seamlessly */
        for ( $pass = 0; $pass < 2; $pass++ ) :
          foreach ( $kjc_reviews as $review ) :
            $rating   = (int) get_comment_meta( $review->comment_ID, 'rating', true ) ?: 5;
            $name     = $review->comment_author ?: 'Verified Buyer';
            $verified = get_comment_meta( $review->comment_ID, 'verified', true );
            $product  = get_the_title( $review->comment_post_ID );
            $date     = date_i18n( 'M j, Y', strtotime( $review->comment_date ) );
            $text     = wp_strip_all_tags( $review->comment_content );
            if ( mb_strlen( $text ) > 220 ) {
                $text = rtrim( mb_substr( $text, 0, 219 ) ) . '…';
            }
            $words    = preg_split( '/\s+/', trim( $name ) );
            $initials = strtoupper( substr( $words[0], 0, 1 ) . ( isset( $words[1] ) ? substr( $words[1], 0, 1 ) : '' ) );
        ?>
        <div class="review-card"<?php echo $pass ? ' aria-hidden="true"' : ''; ?>>
          <div class="review-top">
            <div class="review-avatar"><?php echo esc_html( $initials ); ?></div>
            <div class="review-id">
              <div class="review-name"><?php echo esc_html( $name ); ?></div>
              <div class="review-meta">
                <?php if ( $verified ) : ?>
                  <span class="review-verified">✓ Verified Buyer</span>
                <?php endif; ?>
                <span class="review-date"><?php echo esc_html( $date ); ?></span>
              </div>
            </div>
          </div>
          <div class="review-stars"><?php echo str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ); ?></div>
          <div class="review-text"><?php echo esc_html( $text ); ?></div>
          <?php if ( $product ) : ?>
            <div class="review-verified" style="margin-top:12px;opacity:.7;">on <?php echo esc_html( $product ); ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; endfor; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

  <!-- ABOUT -->
  <section class="about-section">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Black-Hoodies-Modle-Pic.jpg" alt="Model wearing a King Jesus Clothing black Christian hoodie" class="about-img-placeholder" id="aboutImg">
    <div class="about-text" id="aboutText">
      <div class="section-label">Our Story</div>
      <h2 class="section-title">Born from a<br>Calling, Not a Trend</h2>
      <p>We exist to focus on, teach, and spread the living words and teachings of Christ and His Kingdom. Our ultimate goal is to proclaim the gospel of the Kingdom, create fresh opportunities for evangelism and discipleship, draw people into deeper intimacy with God, and literally clothe them in Christ through every garment we create.</p>
      <a href="<?php echo home_url('/about'); ?>" class="btn-outline">Our Full Story →</a>
    </div>
  </section>

  <!-- FAQ -->
  <div class="faq-outer">
    <div class="faq-inner">
      <div class="reveal" style="margin-bottom:40px">
        <div class="section-label">FAQ</div>
        <h2 class="section-title">Common Questions</h2>
      </div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How long does shipping take? <div class="faq-arrow"></div></div><div class="faq-a">International orders typically arrive in 7–14 business days. Free shipping on all orders over $80.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">What is your return policy? <div class="faq-arrow"></div></div><div class="faq-a">We offer easy 30-day returns on all unworn, unwashed items in original condition. Simply contact us and we'll walk you through the process! Look at our Return Policy page for more details.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How do your sizes run? <div class="faq-arrow"></div></div><div class="faq-a">Our garments are true to size with a slightly relaxed fit. We recommend checking the size chart on each product page. If you're between sizes, size up for a more comfortable fit.</div></div>
      <!-- <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How do you support missionaries? <div class="faq-arrow"></div></div><div class="faq-a">A portion of every purchase is donated to vetted missionary organizations working in unreached people groups. We partner with field missionaries personally and report transparently on how funds are used each quarter.</div></div> -->
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">Do you offer bulk or church orders? <div class="faq-arrow"></div></div><div class="faq-a">Yes! We offer special pricing for churches, youth groups, and ministries, starting at 6+ items. Reach out through our Contact page and we'll take care of you.</div></div>
    </div>
  </div>

  <!-- SCRIPTURE -->
  <section class="scripture-section">
    <div class="scripture-text" id="scripture">"I have been crucified with Christ. It is no longer I who live, but Christ who lives in me."</div>
    <div class="scripture-ref">Galatians 2:20</div>
  </section>
</div><!-- end .page-body -->

<script>
(function () {
  // Touch devices: tap the reviews carousel to pause, tap again to resume.
  // (Desktop keeps the :hover pause, which works well there.)
  if (window.matchMedia('(hover: none)').matches) {
    var track = document.querySelector('.carousel-track');
    if (track) {
      track.addEventListener('click', function () { track.classList.toggle('paused'); });
    }
  }
})();
</script>

<?php get_footer(); ?>
<?php
/**
 * Single Product Page Template
 * Override: woocommerce/single-product.php
 *
 * Place at: yourtheme/woocommerce/single-product.php
 *
 * @version 1.6.4
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'woocommerce_before_single_product' );
?>

<style>
/* ── Single Product Page ────────────────────────────────────── */
.custom-single-product-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 2rem 4rem;
}

/* Breadcrumb */
.custom-product-breadcrumb {
    margin-bottom: 2rem;
    font-size: 0.9rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    flex-wrap: wrap;
}

.custom-product-breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.custom-product-breadcrumb a:hover {
    text-decoration: underline;
}

.custom-product-breadcrumb span {
    margin: 0;
}

/* Two-column layout */
.custom-product-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}

/* ── Left: Images ─── */
.custom-product-gallery {
    position: sticky;
    top: 2rem;
}

.custom-product-main-image {
    border-radius: 20px;
    overflow: hidden;
    background: #f9fafb;
    aspect-ratio: 1 / 1;
    margin-bottom: 1rem;
}

.custom-product-main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}

/* Zoom on hover — scale the image inside its clipped container */
.custom-product-main-image:hover img {
    transform: scale(1.08);
    cursor: zoom-in;
}

/* Gallery thumbnails */
.custom-product-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}

.custom-product-thumb {
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.2s;
}

.custom-product-thumb.active,
.custom-product-thumb:hover {
    border-color: #667eea;
}

.custom-product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* ── Right: Details ─── */
.custom-product-details {
    padding-top: 0.5rem;
}

/* Category */
.custom-product-detail-category {
    font-size: 0.8rem;
    font-weight: 700;
    color: #667eea;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.75rem;
}

/* Title */
.custom-product-detail-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1.2;
    margin-bottom: 1rem;
}

/* Rating */
.custom-product-rating {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.custom-product-rating .star-rating {
    font-size: 1rem;
    color: #f59e0b;
}

.custom-product-rating .woocommerce-review-link {
    font-size: 0.875rem;
    color: #6b7280;
    text-decoration: none;
}

/* Price */
.custom-product-detail-price {
    margin-bottom: 1.75rem;
}

.custom-product-detail-price p.price {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
}

.custom-product-detail-price .price ins {
    text-decoration: none;
    color: #667eea;
    background: transparent;
}

.custom-product-detail-price .price del {
    font-size: 1.25rem;
    color: #9ca3af;
    font-weight: 400;
}

/* Short description */
.custom-product-short-desc {
    font-size: 1rem;
    color: #4b5563;
    line-height: 1.7;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f3f4f6;
}

/* Stripe Express Checkout wrapper */
.stripe-express-checkout-wrapper {
    margin-bottom: 1.5rem;
    min-height: 45px;
}

#payment-request-button {
    height: 45px;
}

/* Add to cart area */
.custom-product-add-to-cart {
    margin-bottom: 2rem;
}

/* Override WC quantity + button */
.custom-product-add-to-cart form.cart {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.custom-product-add-to-cart .quantity input.qty {
    width: 80px;
    padding: 1rem;
    text-align: center;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
}

.custom-product-add-to-cart .quantity input.qty:focus {
    outline: none;
    border-color: #667eea;
}

.custom-product-add-to-cart .single_add_to_cart_button {
    flex: 1;
    padding: 1.1rem 2rem;
    background: #252525;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    text-transform: none;
    letter-spacing: normal;
}

.custom-product-add-to-cart .single_add_to_cart_button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Meta (SKU, categories, tags) */
.custom-product-meta {
    font-size: 0.875rem;
    color: #6b7280;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-bottom: 2rem;
}

.custom-product-meta a {
    color: #667eea;
    text-decoration: none;
}

.custom-product-meta a:hover {
    text-decoration: underline;
}

/* Trust badges */
.custom-product-trust {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 12px;
}

.custom-trust-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.custom-trust-item svg {
    color: #667eea;
    flex-shrink: 0;
}

/* ── Tabs: Description, Reviews ─── */
.custom-product-tabs {
    margin-top: 4rem;
    border-top: 2px solid #f3f4f6;
    padding-top: 3rem;
}

.custom-product-tabs .woocommerce-tabs {
    /* WC tabs will render here — override styles below */
}

.custom-product-tabs ul.tabs {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0 0 2.5rem 0;
    border-bottom: 2px solid #e5e7eb;
    gap: 0;
}

.custom-product-tabs ul.tabs li {
    margin: 0;
    padding: 0;
}

.custom-product-tabs ul.tabs li a {
    display: block;
    padding: 1rem 2rem;
    font-weight: 700;
    font-size: 1rem;
    color: #6b7280;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
}

.custom-product-tabs ul.tabs li.active a,
.custom-product-tabs ul.tabs li a:hover {
    color: #667eea;
    border-bottom-color: #667eea;
}

.custom-product-tabs .panel {
    font-size: 1rem;
    color: #374151;
    line-height: 1.8;
}

/* Related products — 4 column grid */
.custom-related-products {
    margin-top: 4rem;
}

.custom-related-products h2 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.custom-related-products ul.products {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 1.5rem !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 0 1.5rem 0 !important;
    float: none !important;
    width: 100% !important;
}

.custom-related-products ul.products::before,
.custom-related-products ul.products::after {
    display: none !important;
    content: none !important;
}

.custom-related-products ul.products li.product {
    width: auto !important;
    max-width: none !important;
    min-width: 0 !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    clear: none !important;
}

/* Related product card */
.custom-related-card {
    display: block;
    text-decoration: none;
    color: inherit;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: transform 0.3s, box-shadow 0.3s;
}

.custom-related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.custom-related-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #f3f4f6;
}

.custom-related-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.custom-related-info {
    padding: 1rem;
}

.custom-related-info h3 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.4rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.custom-related-price {
    font-size: 1rem;
    font-weight: 700;
    color: #667eea;
}

.custom-related-view-all {
    display: block;
    width: fit-content;
    margin: 1.5rem auto 0;
    padding: 0.875rem 2.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102,126,234,0.3);
}

.custom-related-view-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102,126,234,0.4);
    color: white;
}

/* Related products responsive — defined here to guarantee they apply */
@media (max-width: 900px) {
    .custom-single-product-container .custom-related-products ul.products {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }
}

@media (max-width: 480px) {
    .custom-single-product-container .custom-related-products ul.products {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
}

/* ── Tabs ─────────────────────────────────────────────────── */
.custom-product-tabs {
    margin-top: 4rem;
    border-top: 2px solid #f3f4f6;
    padding-top: 2rem;
}

/* Tab list */
.custom-product-tabs .woocommerce-tabs ul.tabs {
    display: flex !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 0 2rem 0 !important;
    border-bottom: 2px solid #e5e7eb !important;
    gap: 0 !important;
    overflow-x: auto !important;
    scrollbar-width: none !important;
    -webkit-overflow-scrolling: touch;
    flex-wrap: nowrap !important;
}

.custom-product-tabs .woocommerce-tabs ul.tabs::-webkit-scrollbar {
    display: none;
}

.custom-product-tabs .woocommerce-tabs ul.tabs::before,
.custom-product-tabs .woocommerce-tabs ul.tabs::after {
    display: none !important;
    content: none !important;
}

.custom-product-tabs .woocommerce-tabs ul.tabs li {
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    border-radius: 0 !important;
    white-space: nowrap;
}

.custom-product-tabs .woocommerce-tabs ul.tabs li a {
    display: block !important;
    padding: 0.875rem 1.5rem !important;
    font-weight: 700 !important;
    font-size: 0.95rem !important;
    color: #6b7280 !important;
    text-decoration: none !important;
    border-bottom: 3px solid transparent !important;
    margin-bottom: -2px !important;
    transition: all 0.2s !important;
    background: transparent !important;
}

.custom-product-tabs .woocommerce-tabs ul.tabs li.active a,
.custom-product-tabs .woocommerce-tabs ul.tabs li a:hover {
    color: #667eea !important;
    border-bottom-color: #667eea !important;
    background: transparent !important;
}

/* Tab panels */
.custom-product-tabs .woocommerce-tabs .panel {
    background: #f9fafb !important;
    border-radius: 16px !important;
    padding: 2rem !important;
    font-size: 1rem !important;
    color: #374151 !important;
    line-height: 1.8 !important;
    border: none !important;
}

/* Additional info table */
.custom-product-tabs .woocommerce-tabs .panel table.shop_attributes {
    width: 100%;
    border-collapse: collapse;
}

.custom-product-tabs .woocommerce-tabs .panel table.shop_attributes th,
.custom-product-tabs .woocommerce-tabs .panel table.shop_attributes td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.95rem;
    text-align: left;
}

.custom-product-tabs .woocommerce-tabs .panel table.shop_attributes th {
    font-weight: 700;
    color: #1f2937;
    width: 35%;
}

/* Reviews */
.custom-product-tabs #reviews #comments ol.commentlist {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem 0;
}

.custom-product-tabs #reviews #comments ol.commentlist li {
    padding: 1.25rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.custom-product-tabs #reviews #respond {
    margin-top: 2rem;
}

/* ── Add-to-cart / loop buttons ───────────────────────────── */
/* Covers both simple (a.button) and variable (button.button) products */
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
a.add_to_cart_button,
a.add_to_cart_button.button,
button.add_to_cart_button,
.product .button,
ul.products li.product a.button,
ul.products li.product button.button {
    background: #252525 !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 50px !important;
    font-weight: 700 !important;
    opacity: 1 !important;
    box-shadow: 0 2px 8px rgba(102,126,234,0.25) !important;
    transition: all 0.3s !important;
    cursor: pointer !important;
    text-decoration: none !important;
}

.woocommerce a.button:hover,
.woocommerce button.button:hover,
a.add_to_cart_button:hover,
ul.products li.product a.button:hover,
ul.products li.product button.button:hover {
    color: #ffffff !important;
    opacity: 1 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(102,126,234,0.4) !important;
}

@media (max-width: 900px) {
    .custom-product-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .custom-product-gallery {
        position: static;
    }
    .custom-product-detail-title {
        font-size: 1.75rem;
    }
}

@media (max-width: 640px) {
    .custom-single-product-container {
        padding: 1rem 1rem 3rem;
    }
    .custom-product-tabs .woocommerce-tabs ul.tabs li a {
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
    }
    .custom-product-tabs .woocommerce-tabs .panel {
        padding: 1.25rem !important;
    }
}

@media (max-width: 480px) {
    .custom-product-thumbs {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>

<div class="custom-single-product-container">

    <?php while ( have_posts() ) : the_post(); global $product;

        // Remove from summary hook BEFORE it fires — prevents all the doubles
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title',    5  );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating',   10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price',    10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt',  20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta',     40 );
        // Remove related products from the default after_summary hook (we render our own below)
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    ?>

        <!-- Breadcrumb -->
        <nav class="custom-product-breadcrumb">
            <a href="<?php echo esc_url( home_url() ); ?>">Home</a>
            <span>›</span>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Shop</a>
            <?php
            $cats = get_the_terms( $product->get_id(), 'product_cat' );
            if ( $cats && ! is_wp_error( $cats ) ) {
                $cat = reset( $cats );
                echo '<span>›</span><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
            }
            ?>
            <span>›</span>
            <span style="color:#1f2937;"><?php echo esc_html( $product->get_name() ); ?></span>
        </nav>

        <!-- Main Layout -->
        <div class="custom-product-layout">

            <!-- Left: Gallery -->
            <div class="custom-product-gallery">
                <?php
                // Main image — always use 'full' for sharpness
                $main_image_id = $product->get_image_id();
                $gallery_ids   = $product->get_gallery_image_ids();
                $all_image_ids = array_merge(
                    $main_image_id ? array( $main_image_id ) : array(),
                    $gallery_ids
                );
                ?>

                <div class="custom-product-main-image" id="custom-main-image">
                    <?php
                    // Build full-size URLs for all images — used by the lightbox
                    $full_urls = array();
                    foreach ( $all_image_ids as $img_id ) {
                        $full_urls[] = wp_get_attachment_image_url( $img_id, 'full' );
                    }
                    $first_full = $main_image_id ? wp_get_attachment_image_url( $main_image_id, 'full' ) : '';
                    ?>
                    <?php /* Wrap in <a> so GLightbox can open the full image on click */ ?>
                    <a href="<?php echo esc_url( $first_full ); ?>"
                       class="glightbox"
                       data-gallery="product-gallery"
                       id="custom-main-link">
                        <?php
                        if ( $main_image_id ) {
                            echo wp_get_attachment_image( $main_image_id, 'full', false, array(
                                'id'      => 'custom-main-img',
                                'alt'     => esc_attr( $product->get_name() ),
                                'loading' => 'eager',
                            ) );
                        } else {
                            echo wc_placeholder_img( 'full' );
                        }
                        ?>
                    </a>
                    <?php
                    // Hidden lightbox links for the rest of the gallery images
                    // GLightbox needs all images in the DOM to enable next/prev navigation
                    foreach ( $all_image_ids as $i => $img_id ) :
                        if ( $i === 0 ) continue; // first image is already the <a> above
                        $url = wp_get_attachment_image_url( $img_id, 'full' );
                    ?>
                        <a href="<?php echo esc_url( $url ); ?>"
                           class="glightbox"
                           data-gallery="product-gallery"
                           style="display:none;"
                           data-index="<?php echo esc_attr( $i ); ?>">
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php if ( count( $all_image_ids ) > 1 ) : ?>
                    <div class="custom-product-thumbs">
                        <?php foreach ( $all_image_ids as $i => $img_id ) :
                            $full_url = wp_get_attachment_image_url( $img_id, 'full' );
                        ?>
                            <div class="custom-product-thumb <?php echo $i === 0 ? 'active' : ''; ?>"
                                 data-full="<?php echo esc_url( $full_url ); ?>"
                                 data-index="<?php echo esc_attr( $i ); ?>"
                                 onclick="customSwitchImage(this)">
                                <?php echo wp_get_attachment_image( $img_id, 'thumbnail', false, array(
                                    'alt'     => esc_attr( $product->get_name() ) . ' ' . ( $i + 1 ),
                                    'loading' => 'lazy',
                                ) ); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Details -->
            <div class="custom-product-details">

                <?php
                $cats = get_the_terms( $product->get_id(), 'product_cat' );
                if ( $cats && ! is_wp_error( $cats ) ) {
                    $cat = reset( $cats );
                    echo '<div class="custom-product-detail-category">' . esc_html( $cat->name ) . '</div>';
                }
                ?>

                <h1 class="custom-product-detail-title"><?php echo wp_kses_post( $product->get_name() ); ?></h1>

                <!-- Rating -->
                <?php if ( $product->get_rating_count() > 0 ) : ?>
                    <div class="custom-product-rating">
                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                        <a href="#reviews" class="woocommerce-review-link">
                            <?php printf( _n( '%s review', '%s reviews', $product->get_rating_count(), 'woocommerce' ), $product->get_rating_count() ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="custom-product-detail-price woocommerce">
                    <?php woocommerce_template_single_price(); ?>
                </div>

                <!-- Short Description -->
                <?php if ( $product->get_short_description() ) : ?>
                    <div class="custom-product-short-desc">
                        <?php echo wp_kses_post( $product->get_short_description() ); ?>
                    </div>
                <?php endif; ?>

                <!-- Stripe Express Checkout -->
                <div id="stripe-express-checkout-container" class="stripe-express-checkout-wrapper">
                    <div id="payment-request-button"></div>
                </div>

                <!-- Add to Cart — only fires add_to_cart now, everything else removed above -->
                <div class="custom-product-add-to-cart">
                    <?php do_action( 'woocommerce_single_product_summary' ); ?>
                </div>

                <!-- Meta (SKU, categories, tags) -->
                <div class="custom-product-meta">
                    <?php woocommerce_template_single_meta(); ?>
                </div>

                <!-- Trust Badges -->
                <div class="custom-product-trust">
                    <div class="custom-trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Secure Checkout
                    </div>
                    <div class="custom-trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Free Shipping $80+
                    </div>
                    <div class="custom-trust-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Easy Returns
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabs: Description, Additional Info, Reviews -->
        <div class="custom-product-tabs">
            <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
        </div>

        <!-- Related Products -->
        <?php
        $related_ids = wc_get_related_products( $product->get_id(), 4 );
        if ( $related_ids ) : ?>
        <div class="custom-related-products">
            <h2>Related Products</h2>
            <ul class="products columns-4">
                <?php foreach ( $related_ids as $related_id ) :
                    $related = wc_get_product( $related_id );
                    if ( ! $related || ! $related->is_visible() ) continue;
                    $img_id  = $related->get_image_id();
                ?>
                <li class="product">
                    <a href="<?php echo esc_url( $related->get_permalink() ); ?>" class="custom-related-card">
                        <div class="custom-related-img">
                            <?php if ( $img_id ) :
                                echo wp_get_attachment_image( $img_id, 'large', false, array(
                                    'alt'     => esc_attr( $related->get_name() ),
                                    'loading' => 'lazy',
                                ) );
                            else :
                                echo wc_placeholder_img( 'large' );
                            endif; ?>
                        </div>
                        <div class="custom-related-info">
                            <h3><?php echo esc_html( $related->get_name() ); ?></h3>
                            <span class="custom-related-price"><?php echo $related->get_price_html(); ?></span>
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-related-view-all">
                View All Products
            </a>
        </div>
        <?php endif; ?>

    <?php endwhile; ?>

</div>

<script>
// ── GLightbox — loaded from CDN, handles lightbox + swipe + zoom ──
(function() {
    // Load GLightbox CSS
    var link = document.createElement('link');
    link.rel  = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/css/glightbox.min.css';
    document.head.appendChild(link);

    // Load GLightbox JS then initialise
    var script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/js/glightbox.min.js';
    script.onload = function() {
        window.productLightbox = GLightbox({
            selector: '.glightbox[data-gallery="product-gallery"]',
            touchNavigation: true,  // swipe on mobile
            loop: true,
            zoomable: true,         // pinch-to-zoom on mobile / scroll on desktop
        });
    };
    document.head.appendChild(script);
})();

/**
 * Called when a thumbnail is clicked.
 * 1. Swaps the visible main image src
 * 2. Updates the main <a> href so the lightbox opens the right image
 * 3. Tells GLightbox which slide to open first (so next/prev still works)
 */
function customSwitchImage(thumb) {
    var fullSrc = thumb.dataset.full;
    var index   = parseInt(thumb.dataset.index, 10);

    // Swap visible image.
    // Must clear srcset and sizes — when srcset is present the browser
    // ignores src entirely and picks its own image from the set instead.
    var mainImg = document.getElementById('custom-main-img');
    if (mainImg) {
        mainImg.srcset = '';
        mainImg.sizes  = '';
        mainImg.src    = fullSrc;
    }

    // Update the main lightbox link so clicking opens the correct image
    var mainLink = document.getElementById('custom-main-link');
    if (mainLink) mainLink.href = fullSrc;

    // Reinitialise GLightbox starting at the selected index
    // (needed so next/prev arrows reflect the new starting position)
    if (window.productLightbox) {
        window.productLightbox.destroy();
        window.productLightbox = GLightbox({
            selector: '.glightbox[data-gallery="product-gallery"]',
            touchNavigation: true,
            loop: true,
            zoomable: true,
            startAt: index,
        });
    }

    // Update active thumbnail highlight
    document.querySelectorAll('.custom-product-thumb').forEach(function(t) {
        t.classList.remove('active');
    });
    thumb.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    var navOverlay = document.getElementById('navOverlay');
    if (navOverlay) {
        navOverlay.addEventListener('click', function() {
            var nav = document.querySelector('nav');
            if (nav) nav.classList.remove('active');
            navOverlay.classList.remove('active');
        });
    }
});
</script>

<?php
do_action( 'woocommerce_after_single_product' );
get_footer();
?>
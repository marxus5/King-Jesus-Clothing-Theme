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
    color: #7A0E1A;
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

/* ── Main image carousel ─────────────────────────────────── */
.custom-product-main-image {
    border-radius: 20px;
    overflow: hidden;
    background: #f9fafb;
    margin-bottom: 1rem;
    position: relative;
    width: 100%;
    max-width: 100%;
}

/* Splide base */
#product-main-carousel {
    border-radius: 20px;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}

#product-main-carousel .splide__track {
    width: 100%;
    overflow: hidden;
}

#product-main-carousel .splide__list {
    width: 100%;
    touch-action: pan-y pinch-zoom;
}

#product-main-carousel .splide__slide {
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #f9fafb;
    cursor: zoom-in;
    width: 100%;
    flex-shrink: 0;
}

#product-main-carousel .splide__slide a {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
}

#product-main-carousel .splide__slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
}

#product-main-carousel .splide__slide:hover img {
    transform: scale(1.06);
}

/* Carousel navigation arrows */
#product-main-carousel .splide__arrows {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    pointer-events: none;
}

#product-main-carousel .splide__arrow {
    position: absolute !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: rgba(255, 255, 255, 0.92) !important;
    border: none !important;
    border-radius: 50% !important;
    width: 42px !important;
    height: 42px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    opacity: 0.85;
    pointer-events: all;
    cursor: pointer;
    transition: opacity 0.25s, box-shadow 0.25s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    z-index: 10 !important;
}

#product-main-carousel .splide__arrow--prev {
    left: 14px !important;
    right: auto !important;
}

#product-main-carousel .splide__arrow--next {
    right: 14px !important;
    left: auto !important;
}

#product-main-carousel .splide__arrow:hover {
    opacity: 1 !important;
    box-shadow: 0 4px 14px rgba(0,0,0,0.22) !important;
}

#product-main-carousel .splide__arrow svg {
    fill: #1f2937 !important;
    width: 18px !important;
    height: 18px !important;
}

/* Carousel dots/pagination */
#product-main-carousel .splide__pagination {
    bottom: 14px !important;
}

#product-main-carousel .splide__pagination__page {
    background: rgba(255, 255, 255, 0.55) !important;
    width: 8px !important;
    height: 8px !important;
    transition: background 0.2s, transform 0.2s !important;
}

#product-main-carousel .splide__pagination__page.is-active {
    background: white !important;
    transform: scale(1.3) !important;
}

/* Smooth slide fade when switching color */
#product-main-carousel .splide__list {
    transition: opacity 0.25s ease;
}
#product-main-carousel.is-switching .splide__list {
    opacity: 0;
}

/* ── Color swatch selector ───────────────────────────────── */
.custom-color-swatches {
    margin-bottom: 1.5rem;
}

.custom-color-swatches-label {
    font-size: 0.875rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.6rem;
}

.custom-color-swatches-label span {
    font-weight: 400;
    color: #6b7280;
    margin-left: 0.4rem;
}

.custom-color-swatches-list {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
}

.custom-color-swatch {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 3px solid transparent;
    outline: 2px solid transparent;
    cursor: pointer;
    transition: outline-color 0.2s, border-color 0.2s, transform 0.2s;
    position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15);
}

.custom-color-swatch:hover {
    transform: scale(1.12);
    outline-color: #7A0E1A;
    outline-offset: 2px;
}

.custom-color-swatch.active {
    border-color: white;
    outline-color: #7A0E1A;
    outline-offset: 2px;
    transform: scale(1.12);
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
    border-color: #7A0E1A;
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
    color: #7A0E1A;
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
    margin-bottom: 0.25rem;
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
    color: #7A0E1A;
    background: transparent;
}

.custom-product-detail-price .price del {
    font-size: 1.5rem;
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

/* WooCommerce Payments Express Checkout (Google Pay / Apple Pay) button spacing */
iframe[title="Secure express checkout frame"],
iframe[name^="__privateStripeFrame"] {
    margin-top: 16px !important;
    margin-bottom: 24px !important;
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
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}

.custom-product-add-to-cart form.cart > :not(.variations) {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Quantity stepper — same - / value / + control as the cart page. The buttons
   are injected by js/qty-stepper.js, which wraps the .quantity in a .kjc-qty. */
.custom-product-add-to-cart .kjc-qty {
    display: inline-flex !important;   /* beat form.cart > :not(.variations) */
    align-items: center;
    gap: 0 !important;
    width: max-content;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.custom-product-add-to-cart .kjc-qty .quantity {
    margin: 0;
    display: inline-flex;
    align-items: center;
}
.custom-product-add-to-cart .kjc-qty__btn {
    width: 42px;
    height: 46px;
    flex: 0 0 auto;
    border: none;
    background: #fff;
    color: #1f2937;
    font-size: 1.3rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
}
.custom-product-add-to-cart .kjc-qty__btn:hover { background: #7A0E1A; color: #fff; }
.custom-product-add-to-cart .kjc-qty input.qty {
    width: 52px;
    height: 46px;
    text-align: center;
    border: none;
    border-left: 1px solid #ededed;
    border-right: 1px solid #ededed;
    border-radius: 0;
    padding: 0;
    font-size: 1.05rem;
    font-weight: 700;
    color: #1f2937;
    background: #fff;
    -moz-appearance: textfield;
    appearance: textfield;
}
.custom-product-add-to-cart .kjc-qty input.qty:focus { outline: none; }
.custom-product-add-to-cart .kjc-qty input.qty::-webkit-outer-spin-button,
.custom-product-add-to-cart .kjc-qty input.qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
/* Fallback before the stepper JS runs (and if it doesn't). */
.custom-product-add-to-cart .quantity input.qty {
    text-align: center;
    font-weight: 700;
    color: #1f2937;
}

.custom-product-add-to-cart .single_add_to_cart_button {
    flex: 1;
    padding: 0.5rem 2rem;
    background: #252525;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(122, 14, 26, 0.3);
    text-transform: none;
    letter-spacing: normal;
}

.custom-product-add-to-cart .single_add_to_cart_button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(122, 14, 26, 0.4);
}

/* Meta (SKU, categories, tags) */
.custom-product-meta {
    font-size: 0.875rem;
    color: #6b7280;
    display: none;
    flex-direction: column;
    gap: 0.4rem;
    margin-bottom: 2rem;
}

.custom-product-meta a {
    color: #7A0E1A;
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
    color: #7A0E1A;
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
    color: #7A0E1A;
    border-bottom-color: #7A0E1A;
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
    color: #7A0E1A;
}

.custom-related-view-all {
    display: block;
    width: fit-content;
    margin: 1.5rem auto 0;
    padding: 0.875rem 2.5rem;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(122, 14, 26, 0.3);
}

.custom-related-view-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(122, 14, 26, 0.4);
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
    color: #7A0E1A !important;
    border-bottom-color: #7A0E1A !important;
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
    .custom-product-main-image {
        width: 100%;
        max-width: 100%;
        border-radius: 20px;
    }
    #product-main-carousel,
    #product-main-carousel .splide__track {
        width: 100%;
        max-width: 100%;
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
    .custom-product-main-image {
        width: 100%;
        max-width: 100%;
        border-radius: 16px;
    }
    #product-main-carousel,
    #product-main-carousel .splide__track,
    #product-main-carousel .splide__slide {
        width: 100%;
        max-width: 100%;
        border-radius: 16px;
    }
}

@media (max-width: 480px) {
    .custom-product-thumbs {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Hide the WooCommerce color select row — swatches handle color selection instead */
.variations tr.color-row,
.variations tr:has(select[name*="color"]),
.variations tr:has(select[name*="colour"]),
.variations tr:has(select[id*="color"]),
.variations tr:has(select[id*="colour"]),
.variations tr:has(select[name="attribute_Color"]),
.variations tr:has(select[name="attribute_Colour"]) {
    display: none !important;
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

                <?php
                // Build full-size URL array for JS
                $full_urls = array();
                foreach ( $all_image_ids as $img_id ) {
                    $full_urls[] = wp_get_attachment_image_url( $img_id, 'full' );
                }

                // Collect color variation image sets (variable products only)
                $color_image_sets = array();
                if ( $product->is_type( 'variable' ) ) {
                    $variations = $product->get_available_variations();

                    // Find the color attribute key directly from variation data.
                    // WooCommerce stores keys as 'attribute_pa_color' (taxonomy) or
                    // 'attribute_Color' (local attribute) — we scan whichever exists.
                    $color_attr_key = '';
                    if ( ! empty( $variations ) ) {
                        foreach ( array_keys( $variations[0]['attributes'] ) as $key ) {
                            if ( stripos( $key, 'color' ) !== false || stripos( $key, 'colour' ) !== false ) {
                                $color_attr_key = $key; // already has 'attribute_' prefix
                                break;
                            }
                        }
                    }



                    if ( $color_attr_key ) {
                        // Built-in palette so swatches look right without taxonomy meta
                        $color_palette = array(
                            'brown'  => '#8B5E3C', 'green'  => '#4A7C59', 'black'  => '#1a1a1a',
                            'white'  => '#f5f5f5', 'red'    => '#c0392b', 'Klein blue' => '#7A0E1A',
                            'navy'   => '#272C43', 'grey'   => '#9ca3af', 'gray'   => '#9ca3af',
                            'pink'   => '#ec4899', 'purple' => '#9476AC', 'yellow' => '#f59e0b',
                            'orange' => '#ea580c', 'beige'  => '#d4b896', 'cream'  => '#fffdd0',
                            'Sand'    => '#A68977', 'khaki'  => '#c3b091', 'Dark Green'  => '#4B5B5A',
                            'Eden Green' => '#254841', 'coral'  => '#ff6b6b', 'maroon' => '#800000',
                            'Apricot'  => '#D9D5C9', "Forest Green" => '#31463D', "Sky Blue" => '#AFBEDB',
                            "Coffee" => '#52302B', "Dark Purple" => '#B39FB0', "Grey Coffee" => '#928377',
                            'Oat Gray' => '#E2DEDB', 'Flower Gray' => '#CFCFD2' , 'Light Blue' => '#7B89A6',
                            'Royal Blue' => '#444A60', 'Dark Blue' => '#4A5575', 'Dark Gray' => '#372C28',
                            'Bean Green' => '#859A89', 'Medium Blue' => '#7D9BC1', 
                            
                        );

                        foreach ( $variations as $variation ) {
                            $attr_val = $variation['attributes'][ $color_attr_key ] ?? '';
                            if ( ! $attr_val ) continue;

                            $variation_obj = wc_get_product( $variation['variation_id'] );
                            if ( ! $variation_obj ) continue;
                            $var_img_id = $variation_obj->get_image_id();
                            if ( ! $var_img_id ) continue;

                            $slug  = sanitize_title( $attr_val );
                            $label = $attr_val;

                            // Priority 1: custom _swatch_color meta set via the hex field in admin
                            $hex = get_post_meta( $variation['variation_id'], '_swatch_color', true );

                            // Priority 2: taxonomy term meta (pa_color attribute plugins)
                            if ( ! $hex ) {
                                $tax_key = str_replace( 'attribute_', '', $color_attr_key );
                                if ( taxonomy_exists( $tax_key ) ) {
                                    $term = get_term_by( 'slug', $slug, $tax_key );
                                    if ( $term ) {
                                        $label = $term->name;
                                        $hex   = get_term_meta( $term->term_id, 'product_color', true )
                                              ?: get_term_meta( $term->term_id, 'color', true );
                                    }
                                }
                            }

                            // Priority 3: built-in palette matched on slug or label keywords (case-insensitive)
                            if ( ! $hex ) {
                                $slug_lower  = strtolower( $slug );
                                $label_lower = strtolower( $label );
                                foreach ( $color_palette as $keyword => $palette_hex ) {
                                    $keyword_lower = strtolower( $keyword );
                                    // Exact match first (e.g. 'sand' === 'sand')
                                    if ( $slug_lower === $keyword_lower || $label_lower === $keyword_lower ) {
                                        $hex = $palette_hex;
                                        break;
                                    }
                                    // Substring match (e.g. 'dark-green' contains 'dark green')
                                    if ( strpos( $slug_lower, $keyword_lower ) !== false
                                      || strpos( $label_lower, $keyword_lower ) !== false ) {
                                        $hex = $palette_hex;
                                        break;
                                    }
                                }
                            }

                            // Priority 4: grey fallback
                            if ( ! $hex ) $hex = '#cccccc';

                            if ( ! isset( $color_image_sets[ $slug ] ) ) {
                                $color_image_sets[ $slug ] = array(
                                    'label'   => $label,
                                    'hex'     => $hex,
                                    'img_ids' => array(),
                                );
                            }
                            if ( ! in_array( $var_img_id, $color_image_sets[ $slug ]['img_ids'] ) ) {
                                $color_image_sets[ $slug ]['img_ids'][] = $var_img_id;
                            }
                        }
                    }
                }

                // JS dataset: { slug: firstSlideIndex }
                // Map each color slug to the index of its FIRST image in $all_image_ids
                // so JS can do a direct splide.go(index) with no URL matching needed.
                $js_color_index = array();
                foreach ( $color_image_sets as $slug => $data ) {
                    foreach ( $all_image_ids as $pos => $img_id ) {
                        if ( in_array( $img_id, $data['img_ids'] ) ) {
                            $js_color_index[ $slug ] = $pos;
                            break; // first matching slide is enough
                        }
                    }
                }
                ?>

                <!-- Main image carousel (Splide) -->
                <div class="custom-product-main-image" id="custom-main-image">
                    <div id="product-main-carousel" class="splide"
                         aria-label="<?php echo esc_attr( $product->get_name() ); ?> images">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php foreach ( $all_image_ids as $i => $img_id ) :
                                    $full_url = wp_get_attachment_image_url( $img_id, 'full' );
                                ?>
                                <li class="splide__slide" data-img-url="<?php echo esc_url( $full_url ); ?>">
                                    <a href="<?php echo esc_url( $full_url ); ?>"
                                       class="glightbox"
                                       data-gallery="product-gallery"
                                       data-index="<?php echo esc_attr( $i ); ?>">
                                        <?php echo wp_get_attachment_image( $img_id, 'large', false, array(
                                            'alt'     => esc_attr( $product->get_name() ) . ( $i > 0 ? ' ' . ( $i + 1 ) : '' ),
                                            'loading' => $i === 0 ? 'eager' : 'lazy',
                                        ) ); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if ( count( $all_image_ids ) > 1 ) : ?>
                    <div class="custom-product-thumbs">
                        <?php foreach ( $all_image_ids as $i => $img_id ) :
                            $full_url = wp_get_attachment_image_url( $img_id, 'full' );
                        ?>
                            <div class="custom-product-thumb <?php echo $i === 0 ? 'active' : ''; ?>"
                                 data-full="<?php echo esc_url( $full_url ); ?>"
                                 data-index="<?php echo esc_attr( $i ); ?>">
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
                

                <!-- Color Swatches (variable products with color attribute only) -->
                <?php if ( ! empty( $color_image_sets ) ) : ?>
                <div class="custom-color-swatches" id="custom-color-swatches">
                    <div class="custom-color-swatches-label">
                        Color: <span id="custom-color-label"><?php echo esc_html( reset( $color_image_sets )['label'] ); ?></span>
                    </div>
                    <div class="custom-color-swatches-list">
                        <?php $first_color = true; foreach ( $color_image_sets as $slug => $data ) :
                        ?>
                        <button type="button"
                                class="custom-color-swatch <?php echo $first_color ? 'active' : ''; ?>"
                                style="background-color: <?php echo esc_attr( $data['hex'] ); ?>;"
                                data-color-slug="<?php echo esc_attr( $slug ); ?>"
                                data-color-label="<?php echo esc_attr( $data['label'] ); ?>"
                                data-color-index="<?php echo esc_attr( $js_color_index[ $slug ] ?? 0 ); ?>"
                                title="<?php echo esc_attr( $data['label'] ); ?>"
                                aria-label="<?php echo esc_attr( $data['label'] ); ?>">
                        </button>
                        <?php $first_color = false; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

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

<?php
        /*
         * Tab Content Swap — must run before woocommerce_after_single_product_summary fires.
         * Description tab     → short description
         * Additional Info tab → full description + attributes table
         */
        add_filter( 'woocommerce_product_tabs', function( $tabs ) {
            global $product;

            // Description tab: show short description
            if ( isset( $tabs['description'] ) ) {
                $tabs['description']['callback'] = function() {
                    global $product;
                    $short = $product->get_short_description();
                    if ( $short ) {
                        echo '<div class="woocommerce-product-details__short-description">';
                        echo wp_kses_post( wpautop( $short ) );
                        echo '</div>';
                    }
                };
            }

            // Additional Information tab: full description + attributes table
            if ( isset( $tabs['additional_information'] ) ) {
                $tabs['additional_information']['callback'] = function() {
                    global $product;
                    $desc = $product->get_description();
                    if ( $desc ) {
                        echo '<div class="woocommerce-product-details__full-description">';
                        echo wp_kses_post( wpautop( $desc ) );
                        echo '</div>';
                    }
                    wc_display_product_attributes( $product );
                };
            }

            return $tabs;
        } );
        ?>

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
(function () {
    'use strict';

    /* ── 1. Load external libraries (Splide + GLightbox) ── */
    function loadCSS(href) {
        var l = document.createElement('link');
        l.rel = 'stylesheet'; l.href = href;
        document.head.appendChild(l);
    }
    function loadJS(src, cb) {
        var s = document.createElement('script');
        s.src = src; s.onload = cb;
        document.head.appendChild(s);
    }

    loadCSS('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');
    loadCSS('https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/css/glightbox.min.css');

    /* ── 2. Boot everything after DOM is ready ── */
    document.addEventListener('DOMContentLoaded', function () {

        /* Hide WooCommerce color select row (JS fallback for browsers without :has()) */
        document.querySelectorAll('.variations select').forEach(function (sel) {
            var name = (sel.name || sel.id || '').toLowerCase();
            if (name.indexOf('color') !== -1 || name.indexOf('colour') !== -1) {
                var row = sel.closest('tr');
                if (row) row.style.display = 'none';
            }
        });

        /* nav overlay */
        var navOverlay = document.getElementById('navOverlay');
        if (navOverlay) {
            navOverlay.addEventListener('click', function () {
                var nav = document.querySelector('nav');
                if (nav) nav.classList.remove('active');
                navOverlay.classList.remove('active');
            });
        }

        /* ── Splide carousel ── */
        var splideEl = document.getElementById('product-main-carousel');
        if (!splideEl) return;

        loadJS('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js', function () {

            window.productSplide = new Splide('#product-main-carousel', {
                type        : 'loop',
                perPage     : 1,
                arrows      : true,
                pagination  : true,
                keyboard    : true,
                drag        : true,
                swipeDistanceThreshold: 20,
                speed       : 380,
                easing      : 'cubic-bezier(0.25,0.1,0.25,1)',
                autoWidth   : false,
                autoHeight  : false,
            }).mount();
            
            /* Force proper dimensions after mount */
            setTimeout(function() {
                if (window.productSplide) {
                    window.productSplide.refresh();
                }
            }, 100);

            /* Keep thumbnail highlight in sync when carousel slides */
            window.productSplide.on('moved', function (newIndex) {
                syncThumb(newIndex);
            });

            /* ── GLightbox ── */
            loadJS('https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/js/glightbox.min.js', function () {
                initLightbox(0);
            });
        });
    });

    /* ── 3. Helpers ── */

    function initLightbox(startAt) {
        if (window.productLightbox) {
            window.productLightbox.destroy();
        }
        window.productLightbox = GLightbox({
            selector        : '.splide__slide:not(.splide__slide--clone) a.glightbox[data-gallery="product-gallery"]',
            touchNavigation : true,
            loop            : true,
            zoomable        : true,
            startAt         : startAt,
        });
    }

    function syncThumb(index) {
        document.querySelectorAll('.custom-product-thumb').forEach(function (t) {
            t.classList.toggle('active', parseInt(t.dataset.index, 10) === index);
        });
    }

    /* ── 4. Thumbnail click — jump carousel to that slide ── */
    document.addEventListener('click', function (e) {
        var thumb = e.target.closest('.custom-product-thumb');
        if (!thumb) return;
        var index = parseInt(thumb.dataset.index, 10);
        if (window.productSplide) {
            window.productSplide.go(index);
        }
        syncThumb(index);
        /* Re-init lightbox so next/prev starts from this image */
        if (window.productLightbox) {
            initLightbox(index);
        }
    });

    /* ── 5. Color swatch click — swap carousel images ── */
    document.addEventListener('click', function (e) {
        var swatch = e.target.closest('button.custom-color-swatch');
        if (!swatch) return;

        /* Update active swatch */
        document.querySelectorAll('.custom-color-swatch').forEach(function (s) {
            s.classList.remove('active');
        });
        swatch.classList.add('active');

        /* Update label */
        var labelEl = document.getElementById('custom-color-label');
        if (labelEl) labelEl.textContent = swatch.dataset.colorLabel;

        /* Jump directly to the pre-computed slide index for this color */
        var targetIndex = parseInt(swatch.dataset.colorIndex, 10);
        if (isNaN(targetIndex)) targetIndex = 0;

        var splide = window.productSplide;
        if (!splide) return;

        splide.go(targetIndex);
        syncThumb(targetIndex);
        if (window.productLightbox) {
            initLightbox(targetIndex);
        }

        /* ── Sync WooCommerce variation select so Add to Cart works ──
         * WooCommerce names the select by attribute slug, e.g.:
         *   Local attribute "Color"  → name="attribute_Color"
         *   Taxonomy attribute       → name="attribute_pa_color"
         * We try both, plus a broad fallback on any select whose name
         * contains "color" / "colour" (case-insensitive).
         * The label value (e.g. "Brown") is tried first, then the slug.
         */
        var slug  = swatch.dataset.colorSlug;
        var label = swatch.dataset.colorLabel;
        if (slug || label) {
            /* Build candidate select names */
            var selectNames = [
                'attribute_Color', 'attribute_Colour',
                'attribute_color', 'attribute_colour',
                'attribute_pa_color', 'attribute_pa_colour'
            ];
            document.querySelectorAll('select').forEach(function (sel) {
                var name = (sel.name || sel.id || '').toLowerCase();
                var isColorSel = selectNames.some(function(n){ return sel.name === n || sel.id === n; })
                              || name.indexOf('color') !== -1
                              || name.indexOf('colour') !== -1;
                if (!isColorSel) return;

                /* Try matching by label first (e.g. "Brown"), then by slug */
                var valuesToTry = [label, slug];
                var matched = false;
                valuesToTry.forEach(function(val) {
                    if (matched || !val) return;
                    /* Direct value match */
                    var opt = sel.querySelector('option[value="' + val + '"]');
                    /* Case-insensitive fallback */
                    if (!opt) {
                        Array.from(sel.options).forEach(function(o) {
                            if (!matched && o.value.toLowerCase() === val.toLowerCase()) opt = o;
                        });
                    }
                    if (opt) {
                        sel.value = opt.value;
                        sel.dispatchEvent(new Event('change', { bubbles: true }));
                        matched = true;
                    }
                });
            });
        }
    });

})();
</script>

<?php
/**
 * ─── Tab Content Swap ───────────────────────────────────────
 * Description tab: Show short description only
 * Additional Information tab: Show full description + attributes
 */
add_filter( 'woocommerce_product_tabs', function( $tabs ) {
    global $product;
    
    // Swap Description tab callback to show only short description
    $tabs['description']['callback'] = function() {
        global $product;
        if ( ! $product->get_short_description() ) {
            return;
        }
        ?>
        <div class="panel" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
            <?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?>
        </div>
        <?php
    };
    
    // Swap Additional Information tab callback to show full description + attributes
    $tabs['additional_information']['callback'] = function() {
        global $product;
        
        // Output full description (post content)
        ?>
        <div class="panel" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information">
            <?php
            // Full product description
            if ( $product->get_description() ) {
                echo '<div class="product-description">';
                echo wp_kses_post( wpautop( $product->get_description() ) );
                echo '</div>';
            }
            
            // Attributes table
            wc_display_product_attributes( $product );
            ?>
        </div>
        <?php
    };
    
    return $tabs;
} );

do_action( 'woocommerce_after_single_product' );
get_footer();
?>
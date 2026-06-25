<?php
/**
 * Product Card Template (used in the shop grid)
 * Override: woocommerce/content-product.php
 *
 * Place at: yourtheme/woocommerce/content-product.php
 *
 * Key change: uses 'full' image size to prevent blurry thumbnails.
 * The CSS constrains the display size — the image itself is always sharp.
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Only show visible products
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>

<style>
/* ── Product Card ───────────────────────────────────────────── */
.custom-product-card {
    background: white;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex !important;
    flex-direction: column;
    position: relative;
    width: 100% !important;      /* fill grid cell, never wider */
    max-width: none !important;
    box-sizing: border-box;
}

.custom-product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.13);
}

/* Image */
.custom-product-image-wrap {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #f9fafb;
}

.custom-product-image-wrap a {
    display: block;
    width: 100%;
    height: 100%;
}

.custom-product-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.custom-product-card:hover .custom-product-image-wrap img {
    transform: scale(1.05);
}

/* Sale badge */
.custom-product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    z-index: 1;
}

/* Out of stock badge */
.custom-product-oos {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #6b7280;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    z-index: 1;
}

/* Wishlist / quick-action area */
.custom-product-actions-top {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Info area */
.custom-product-info {
    padding: 1.25rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 0.5rem;
}

/* Category label */
.custom-product-category {
    font-size: 0.75rem;
    font-weight: 600;
    color: #7A0E1A;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* Product name */
.custom-product-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1.4;
}

.custom-product-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.custom-product-name a:hover {
    color: #7A0E1A;
}

/* Short description */
.custom-product-excerpt {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Price */
.custom-product-price {
    margin-top: auto;
    padding-top: 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
}

.custom-product-price .price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.custom-product-price .price ins {
    text-decoration: none;
    color: #7A0E1A;
}

.custom-product-price .price del {
    font-size: 0.9rem;
    color: #9ca3af;
    font-weight: 400;
}

/* Add to cart button */
.custom-product-card .button,
.custom-product-card .add_to_cart_button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6rem 1.25rem;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
    color: white !important;
    border: none;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(122, 14, 26, 0.3);
}

.custom-product-card .button:hover,
.custom-product-card .add_to_cart_button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(122, 14, 26, 0.4);
    color: white !important;
}

.custom-product-card .added_to_cart {
    display: none; /* hide "view cart" link that appears after adding */
}
</style>

<li <?php wc_product_class( 'custom-product-card', $product ); ?>>

    <!-- Product Image -->
    <div class="custom-product-image-wrap">
        <?php
        // Badge: sale or out of stock
        if ( ! $product->is_in_stock() ) {
            echo '<span class="custom-product-oos">Out of Stock</span>';
        } elseif ( $product->is_on_sale() ) {
            echo '<span class="custom-product-badge">Sale</span>';
        }
        ?>
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" tabindex="-1">
            <?php
            // 'full' size — prevents blurry thumbnails at all screen sizes.
            // CSS (object-fit: cover) handles display sizing, never the image itself.
            echo $product->get_image( 'full', array(
                'loading' => 'lazy',
                'alt'     => esc_attr( $product->get_name() ),
            ) );
            ?>
        </a>
    </div>

    <!-- Product Info -->
    <div class="custom-product-info">

        <?php
        // Category label
        $cats = get_the_terms( $product->get_id(), 'product_cat' );
        if ( $cats && ! is_wp_error( $cats ) ) {
            $cat = reset( $cats );
            echo '<span class="custom-product-category">' . esc_html( $cat->name ) . '</span>';
        }
        ?>

        <h2 class="custom-product-name">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                <?php echo wp_kses_post( $product->get_name() ); ?>
            </a>
        </h2>

        <?php
        // Short description
        $short_desc = $product->get_short_description();
        if ( $short_desc ) {
            echo '<p class="custom-product-excerpt">' . wp_kses_post( wp_strip_all_tags( $short_desc ) ) . '</p>';
        }
        ?>

        <div class="custom-product-price">
            <?php echo $product->get_price_html(); ?>
        </div>

    </div>

</li>
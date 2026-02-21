<?php
/**
 * ─────────────────────────────────────────────────────────────────────────────
 * ADD THIS CODE TO YOUR THEME'S functions.php
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * This registers the custom "Your Order" box as a WooCommerce AJAX fragment.
 * WooCommerce will automatically call woocommerce_update_order_review_fragments
 * whenever the checkout recalculates (coupon apply/remove, address entry, etc.)
 * and replace .custom-order-review-inner with fresh HTML — no page refresh needed.
 *
 * The free shipping logic checks the *chosen* shipping method (what WC will
 * actually charge), not just available rates, so the total is always accurate.
 */

/**
 * Returns true if the customer's currently selected shipping method is free shipping.
 * Checks chosen_shipping_methods in session — this is the method WC bills them for.
 */
function custom_checkout_is_free_shipping_chosen() {
    $chosen_methods = WC()->session ? WC()->session->get( 'chosen_shipping_methods', array() ) : array();
    foreach ( $chosen_methods as $method ) {
        if ( strpos( $method, 'free_shipping' ) !== false ) {
            return true;
        }
    }
    return false;
}

/**
 * Builds and returns the HTML for the custom order review inner panel.
 * Called both on initial page load (from the template) and during AJAX updates.
 */
function custom_checkout_order_review_html() {
    ob_start();
    $cart         = WC()->cart;
    $free_chosen  = custom_checkout_is_free_shipping_chosen();
    ?>

    <h3>Your Order</h3>

    <?php foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) :
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) : ?>
        <div class="custom-order-item">
            <div>
                <span class="custom-order-item-name"><?php echo wp_kses_post( $_product->get_name() ); ?></span>
                <span class="custom-order-item-quantity">× <?php echo esc_html( $cart_item['quantity'] ); ?></span>
            </div>
            <span><?php echo apply_filters( 'woocommerce_cart_item_subtotal', $cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></span>
        </div>
    <?php endif; endforeach; ?>

    <div class="custom-order-totals">

        <!-- Subtotal (products only, before shipping/tax) -->
        <div class="custom-totals-row">
            <span>Subtotal</span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <!-- Coupons -->
        <?php foreach ( $cart->get_coupons() as $code => $coupon ) : ?>
            <div class="custom-totals-row">
                <span>Coupon: <?php echo esc_html( $code ); ?></span>
                <span>-<?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Shipping: show FREE if free_shipping is the chosen method, otherwise show flat rate -->
        <?php if ( $cart->needs_shipping() && $cart->show_shipping() ) : ?>
            <div class="custom-totals-row">
                <span>Shipping</span>
                <span>
                    <?php if ( $free_chosen ) : ?>
                        <strong style="color: #1F2937; font-size: 1.25rem;">FREE</strong>
                    <?php else : ?>
                        <?php wc_cart_totals_shipping_html(); ?>
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Fees -->
        <?php foreach ( $cart->get_fees() as $fee ) : ?>
            <div class="custom-totals-row">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

         <!-- Tax -->
        <?php if ( wc_tax_enabled() && ! $cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( $cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="custom-totals-row">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="custom-totals-row">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Grand Total — WC calculates this correctly (free shipping has cost=0) -->
        <div class="custom-totals-row total">
            <span>Total</span>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

    </div>

    <?php
    return ob_get_clean();
}

/**
 * Register .custom-order-review-inner as a WooCommerce AJAX fragment.
 *
 * WC fires woocommerce_update_order_review_fragments during every
 * update_order_review AJAX call and replaces matching DOM elements.
 * The key must exactly match the CSS selector used in the template div.
 */
add_filter( 'woocommerce_update_order_review_fragments', function( $fragments ) {
    $fragments['.custom-order-review-inner'] =
        '<div class="custom-order-review-inner">' . custom_checkout_order_review_html() . '</div>';
    return $fragments;
} );


function printshop_enqueue_styles() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'printshop_enqueue_styles');

// Add WooCommerce support
function printshop_add_woocommerce_support() {
    
}
add_action('after_setup_theme', 'printshop_add_woocommerce_support');

// Register menu
function printshop_register_menus() {
    register_nav_menus(array(
        'primary' => 'Primary Menu'
    ));
}
add_action('after_setup_theme', 'printshop_register_menus');

// Declare WooCommerce support (important!)
function printshop_woocommerce_setup() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 300,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));

    // Gallery features — must be inside this hook to load at the right time
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // General WordPress theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'printshop_woocommerce_setup' );
?>
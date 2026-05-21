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

    wp_enqueue_style(
        'main-style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );

}
add_action('wp_enqueue_scripts', 'printshop_enqueue_styles');

/**
 * Enqueue Quick Checkout & Banner Manager Script
 */
function printshop_enqueue_quick_checkout_script() {
    // Enqueue the custom quick checkout script
    wp_enqueue_script(
        'quick-checkout',
        get_template_directory_uri() . '/js/quick-checkout.js',
        array(),
        filemtime(get_template_directory() . '/js/quick-checkout.js'),
        true // Load in footer
    );
    
    // Only enqueue Stripe.js on product pages
    if ( is_product() || is_checkout() ) {
        // Enqueue Stripe.js library from CDN
        wp_enqueue_script(
            'stripe-js',
            'https://js.stripe.com/v3/',
            array(),
            '3',
            false // Load in header
        );
        
        // Get Stripe public key from WooCommerce Stripe gateway settings
        $stripe_pub_key = get_option('woocommerce_stripe_settings');
        $pub_key = '';
        
        if (is_array($stripe_pub_key) && isset($stripe_pub_key['publishable_key'])) {
            $pub_key = $stripe_pub_key['publishable_key'];
        }
        
        // Pass Stripe config to JavaScript
        if ($pub_key) {
            wp_localize_script(
                'quick-checkout',
                'stripe_config',
                array(
                    'publicKey' => $pub_key,
                    'currency' => strtolower(get_woocommerce_currency()),
                )
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'printshop_enqueue_quick_checkout_script');

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

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * ORDER STATUS & EMAIL FIXES
 * ─────────────────────────────────────────────────────────────────────────────
 */

/**
 * Ensure emails are sent when order status changes.
 * This hook catches any status changes and triggers the appropriate email.
 */
add_action( 'woocommerce_order_status_changed', function( $order_id, $old_status, $new_status, $order ) {
    // Only send emails for non-processing statuses on transition FROM processing
    if ( 'processing' === $old_status && $new_status !== 'processing' ) {
        // Trigger the appropriate email based on new status
        do_action( 'woocommerce_order_status_' . $new_status, $order_id, $order );
    }
}, 10, 4 );

/**
 * Ensure completed orders automatically send their email.
 * Some payment gateways don't trigger this automatically.
 */
add_action( 'woocommerce_payment_complete', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( $order && ! $order->has_status( 'completed' ) ) {
        // Mark as completed which triggers the email
        $order->set_status( 'completed' );
        $order->save();
    }
}, 5 );

/**
 * Fix for Stripe: Ensure processing orders with successful payment are marked complete.
 * Stripe may leave orders in "processing" even after successful payment.
 */
add_action( 'woocommerce_thankyou', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( $order ) {
        // If payment is complete but order is still processing, mark it as completed
        if ( $order->is_paid() && $order->has_status( 'processing' ) ) {
            $order->set_status( 'completed' );
            $order->save();
            // The email will be sent automatically by WooCommerce
        }
    }
}, 5 );


/**
 * Variation Swatch Hex Color
 * ─────────────────────────────────────────────────────────────────
 * Adds a "Swatch Color" hex field to every WooCommerce variation in
 * the admin. The value is saved as variation meta _swatch_color and
 * read by single-product.php to color the swatch circles.
 *
 * INSTALL: paste this entire file's contents into your theme's
 *          functions.php  (or drop it in as a must-use plugin).
 * ─────────────────────────────────────────────────────────────────
 */
 
// ── 1. Render the hex field inside each variation panel ──────────
add_action( 'woocommerce_product_after_variable_attributes', 'kjc_variation_swatch_color_field', 10, 3 );
function kjc_variation_swatch_color_field( $loop, $variation_data, $variation ) {
    $value = get_post_meta( $variation->ID, '_swatch_color', true ) ?: '#cccccc';
    ?>
    <div class="form-row form-row-full kjc-swatch-color-row">
        <label for="kjc_swatch_color_<?php echo esc_attr( $loop ); ?>">
            <?php esc_html_e( 'Swatch Color', 'woocommerce' ); ?>
            <span style="font-weight:400;color:#666;margin-left:6px;">
                (hex, e.g. <code>#8B5E3C</code> for Coffee/Brown)
            </span>
        </label>
        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
            <input type="color"
                   value="<?php echo esc_attr( $value ); ?>"
                   style="width:48px;height:34px;padding:2px;border:1px solid #ddd;border-radius:4px;cursor:pointer;"
                   oninput="document.getElementById('kjc_swatch_color_<?php echo esc_attr( $loop ); ?>').value=this.value"
            >
            <input type="text"
                   id="kjc_swatch_color_<?php echo esc_attr( $loop ); ?>"
                   name="kjc_swatch_color[<?php echo esc_attr( $loop ); ?>]"
                   value="<?php echo esc_attr( $value ); ?>"
                   placeholder="#cccccc"
                   style="max-width:110px;"
                   oninput="this.previousElementSibling.value=this.value"
            >
        </div>
    </div>
    <?php
}
 
// ── 2. Save the hex value when variations are saved ──────────────
add_action( 'woocommerce_save_product_variation', 'kjc_save_variation_swatch_color', 10, 2 );
function kjc_save_variation_swatch_color( $variation_id, $loop ) {
    if ( isset( $_POST['kjc_swatch_color'][ $loop ] ) ) {
        $hex = sanitize_hex_color( wp_unslash( $_POST['kjc_swatch_color'][ $loop ] ) );
        if ( $hex ) {
            update_post_meta( $variation_id, '_swatch_color', $hex );
        }
    }
}


?>
<?php

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * META (INSTAGRAM/FACEBOOK SHOP) CHECKOUT LINK HANDLER
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * Meta sends customers to a URL like:
 *   /shop/checkout-link/?products=367:1,123:2&coupon=CODE&cart_origin=instagram
 *
 * That page doesn't exist on the site, so this intercepts any request whose
 * path contains "checkout-link", loads the requested products/quantities into
 * the WooCommerce cart, applies the coupon (if given), and redirects to the
 * real checkout page.
 * ─────────────────────────────────────────────────────────────────────────────
 */
add_action( 'template_redirect', 'kjc_meta_checkout_link_redirect' );
function kjc_meta_checkout_link_redirect() {

    if ( strpos( $_SERVER['REQUEST_URI'], 'checkout-link' ) === false ) {
        return;
    }

    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return;
    }

    $products_param = isset( $_GET['products'] ) ? sanitize_text_field( wp_unslash( $_GET['products'] ) ) : '';
    $coupon_param   = isset( $_GET['coupon'] ) ? sanitize_text_field( wp_unslash( $_GET['coupon'] ) ) : '';

    if ( $products_param ) {
        WC()->cart->empty_cart();

        foreach ( explode( ',', $products_param ) as $entry ) {
            $parts      = explode( ':', $entry );
            $product_id = absint( $parts[0] ?? 0 );
            $quantity   = isset( $parts[1] ) ? absint( $parts[1] ) : 1;

            if ( $product_id && $quantity > 0 ) {
                WC()->cart->add_to_cart( $product_id, $quantity );
            }
        }
    }

    if ( $coupon_param && ! WC()->cart->has_discount( $coupon_param ) ) {
        WC()->cart->apply_coupon( wc_format_coupon_code( $coupon_param ) );
    }

    wp_safe_redirect( wc_get_checkout_url() );
    exit;
}

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


/**
 * ─────────────────────────────────────────────────────────────────────────────
 * TAPSTITCH SHIPPING NOTIFICATION
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * When Tapstitch fulfills an order it posts a private note containing a
 * tracking URL (gofo.com/us/track). This hook:
 *   1. Detects that note when it is inserted
 *   2. Extracts the tracking URL
 *   3. Saves the tracking URL as order meta so it can be used anywhere
 *   4. Marks the order as "completed"
 *   5. Sends the customer a shipping confirmation email with the tracking link
 *
 * No plugin needed — works purely through WooCommerce hooks.
 * ─────────────────────────────────────────────────────────────────────────────
 */
add_action( 'woocommerce_order_note_added', 'kjc_tapstitch_handle_tracking_note', 10, 2 );

function kjc_tapstitch_handle_tracking_note( $note_id, $order ) {

    // ── Get the note content from the database ──────────────────────────────
    $note = get_comment( $note_id );
    if ( ! $note ) return;

    $note_content = $note->comment_content;

    // ── Detect Tapstitch shipping notes ─────────────────────────────────────
    // Match on Tapstitch's consistent note language rather than a specific
    // carrier URL — works regardless of which carrier they use
    // (gofo.com, USPS, DHL, FedEx, etc.)
    $is_tapstitch_note = (
        strpos( $note_content, 'Your package has been shipped' ) !== false ||
        strpos( $note_content, 'check the logistics information' ) !== false ||
        strpos( $note_content, 'gofo.com' ) !== false ||
        strpos( $note_content, 'tapstitch' ) !== false
    );

    if ( ! $is_tapstitch_note ) return;

    // ── Prevent running twice if somehow the note fires more than once ──────
    if ( get_post_meta( $order->get_id(), '_kjc_tapstitch_notified', true ) ) return;

    // ── Extract any tracking URL from the note ──────────────────────────────
    // Grabs the first https:// URL found — works for any carrier
    preg_match( '/(https?:\/\/[^\s<>"]+)/', $note_content, $matches );
    $tracking_url = ! empty( $matches[1] ) ? esc_url_raw( $matches[1] ) : '';

    // ── Identify the carrier for the email ──────────────────────────────────
    // Add more carriers here if Tapstitch switches in the future
    $carrier = 'your carrier';
    $carrier_patterns = array(
        'usps'       => 'USPS',
        'ups'        => 'UPS',
        'fedex'      => 'FedEx',
        'dhl'        => 'DHL',
        'gofo'       => 'GoFo',
        'auspost'    => 'Australia Post',
        'royalmail'  => 'Royal Mail',
        'canadapost' => 'Canada Post',
    );
    $note_lower = strtolower( $note_content );
    foreach ( $carrier_patterns as $keyword => $carrier_name ) {
        if ( strpos( $note_lower, $keyword ) !== false ) {
            $carrier = $carrier_name;
            break;
        }
    }
    $order->update_meta_data( '_kjc_shipping_carrier', $carrier );

    // ── Save tracking URL to order meta ─────────────────────────────────────
    if ( $tracking_url ) {
        $order->update_meta_data( '_kjc_tracking_url', $tracking_url );
    }

    // ── Mark as notified so this only fires once per order ──────────────────
    $order->update_meta_data( '_kjc_tapstitch_notified', true );
    $order->save();

    // ── Move order to completed ─────────────────────────────────────────────
    // Remove our own hook temporarily to avoid any loops, then update status
    remove_action( 'woocommerce_order_note_added', 'kjc_tapstitch_handle_tracking_note', 10 );
    $order->update_status( 'completed', 'Tapstitch confirmed shipment. Customer notified.' );
    add_action( 'woocommerce_order_note_added', 'kjc_tapstitch_handle_tracking_note', 10, 2 );

    // ── Send customer shipping email ────────────────────────────────────────
    kjc_send_shipping_email( $order, $tracking_url, $carrier );
}


/**
 * Sends a branded shipping confirmation email to the customer.
 * Called automatically by kjc_tapstitch_handle_tracking_note().
 *
 * @param WC_Order $order        The WooCommerce order object
 * @param string   $tracking_url The gofo.com tracking URL from Tapstitch
 */
function kjc_send_shipping_email( $order, $tracking_url, $carrier = 'your carrier' ) {

    $to      = $order->get_billing_email();
    $name    = $order->get_billing_first_name();
    $subject = 'Your King Jesus Clothing Order Has Shipped! 🙏';

    // ── Build the order items summary ───────────────────────────────────────
    $items_html = '';
    foreach ( $order->get_items() as $item ) {
        $items_html .= '<tr>
            <td style="padding:8px 0;border-bottom:1px solid #f3f4f6;font-size:14px;color:#374151;">'
                . esc_html( $item->get_name() )
            . '</td>
            <td style="padding:8px 0;border-bottom:1px solid #f3f4f6;font-size:14px;color:#374151;text-align:right;">x'
                . esc_html( $item->get_quantity() )
            . '</td>
        </tr>';
    }

    // ── Build tracking button HTML ──────────────────────────────────────────
    $tracking_button = '';
    if ( $tracking_url ) {
        $tracking_button = '
        <div style="text-align:center;margin:32px 0;">
            <p style="font-size:13px;color:#6b7280;margin:0 0 12px;">
                Shipping via <strong style="color:#1D1D1D;">' . esc_html( $carrier ) . '</strong>
            </p>
            <a href="' . esc_url( $tracking_url ) . '"
               style="display:inline-block;background:#CE202F;color:#ffffff;text-decoration:none;
                      padding:14px 36px;font-weight:700;font-size:14px;letter-spacing:0.1em;
                      text-transform:uppercase;border-bottom:3px solid #C9A84C;">
                Track My Order
            </a>
        </div>';
    }

    // ── Full email HTML ─────────────────────────────────────────────────────
    $message = '
    <!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
    <body style="margin:0;padding:0;background:#f9fafb;font-family:\'Segoe UI\',system-ui,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;padding:40px 20px;">
            <tr><td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:560px;background:#ffffff;border-top:4px solid #CE202F;">

                    <!-- Header -->
                    <tr>
                        <td style="padding:32px 40px 24px;text-align:center;border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:11px;letter-spacing:0.3em;text-transform:uppercase;
                                        color:#8B5E3C;font-weight:700;margin-bottom:8px;">
                                King Jesus Clothing
                            </div>
                            <h1 style="font-size:24px;font-weight:800;color:#1D1D1D;margin:0;line-height:1.2;">
                                Your Order Is On Its Way! ✝
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 40px;">
                            <p style="font-size:15px;color:#374151;line-height:1.7;margin:0 0 20px;">
                                Hi ' . esc_html( $name ) . ',
                            </p>
                            <p style="font-size:15px;color:#374151;line-height:1.7;margin:0 0 24px;">
                                Great news — your order has been fulfilled and is on its way to you.
                                We pray it blesses you and everyone who sees it. 🙏
                            </p>

                            <!-- Order summary -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="margin-bottom:24px;">
                                <tr>
                                    <td style="font-size:11px;font-weight:700;letter-spacing:0.15em;
                                               text-transform:uppercase;color:#8B5E3C;padding-bottom:8px;">
                                        Order #' . esc_html( $order->get_order_number() ) . '
                                    </td>
                                </tr>
                                ' . $items_html . '
                            </table>

                            ' . $tracking_button . '

                            <p style="font-size:13px;color:#6b7280;line-height:1.7;margin:24px 0 0;text-align:center;">
                                If the button does not work, copy and paste this link into your browser:<br>
                                <a href="' . esc_url( $tracking_url ) . '"
                                   style="color:#CE202F;word-break:break-all;">'
                                   . esc_html( $tracking_url ) .
                                '</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background:#f9fafb;text-align:center;
                                   border-top:1px solid #f3f4f6;">
                            <p style="font-size:12px;color:#9ca3af;margin:0;line-height:1.7;">
                                © ' . date('Y') . ' King Jesus Clothing. All rights reserved.<br>
                                Questions? Reply to this email or visit
                                <a href="' . esc_url( home_url('/contact') ) . '"
                                   style="color:#CE202F;">our contact page</a>.
                            </p>
                        </td>
                    </tr>

                </table>
            </td></tr>
        </table>
    </body>
    </html>';

    // ── Email headers — HTML format, from your store ────────────────────────
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: King Jesus Clothing <' . get_option('admin_email') . '>',
    );

    wp_mail( $to, $subject, $message, $headers );
}


/**
 * ── Suppress premature textdomain notice from WooCommerce core ──────────────
 * This is a known WooCommerce issue — not caused by our theme code.
 */
add_filter( 'doing_it_wrong_trigger_error', function( $trigger, $function_name ) {
    if ( $function_name === '_load_textdomain_just_in_time' ) {
        return false;
    }
    return $trigger;
}, 10, 2 );
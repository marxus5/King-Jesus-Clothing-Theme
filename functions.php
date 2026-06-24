<?php

/**
 * ─────────────────────────────────────────────────────────────────────────────
 * POST/REDIRECT/GET FOR ADD-TO-CART
 * ─────────────────────────────────────────────────────────────────────────────
 * The default WooCommerce single-product add-to-cart is a POST form. Without a
 * redirect, the POST stays in browser history, so hitting the Back button
 * re-submits it and adds the item again. Redirecting to the cart after a
 * non-AJAX POST add-to-cart turns it into a GET history entry (so Back is
 * safe) and takes the customer straight to their cart.
 */
add_filter( 'woocommerce_add_to_cart_redirect', 'kjc_prg_add_to_cart_redirect', 20 );
function kjc_prg_add_to_cart_redirect( $url ) {
    // Respect an existing redirect (e.g. another plugin's choice).
    if ( $url || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) {
        return $url;
    }

    $method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( $_SERVER['REQUEST_METHOD'] ) : '';
    if ( 'POST' === $method && ! empty( $_REQUEST['add-to-cart'] ) ) {
        return function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart' );
    }

    return $url;
}

/**
 * Change the "added to cart" notice button from "View cart" to
 * "Continue Shopping", linking back to the main shop page.
 */
add_filter( 'wc_add_to_cart_message_html', 'kjc_continue_shopping_message', 10, 2 );
function kjc_continue_shopping_message( $message, $products ) {
    $names = array();
    if ( is_array( $products ) ) {
        foreach ( $products as $product_id => $qty ) {
            $names[] = ( $qty > 1 ? absint( $qty ) . ' × ' : '' ) . get_the_title( $product_id );
        }
    }
    $added_text = $names ? implode( ', ', $names ) . ' added to your cart.' : 'Added to your cart.';

    $shop_url = wc_get_page_permalink( 'shop' );
    $button   = sprintf(
        '<a href="%s" class="button wc-forward">%s</a>',
        esc_url( $shop_url ),
        esc_html__( 'Continue Shopping', 'woocommerce' )
    );

    return sprintf( '%s %s', $button, esc_html( $added_text ) );
}

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

    // Combined stylesheet: global chrome (banner, navbar, mobile menu, modal,
    // sticky promo) + homepage template. Loads after style.css so it can layer
    // on top. Replaces the old inline <style> blocks and homepage-v2.css.
    wp_enqueue_style(
        'kjc-main',
        get_template_directory_uri() . '/css/main.css',
        array( 'main-style' ),
        filemtime( get_template_directory() . '/css/main.css' )
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
 * CART HELPERS — free-shipping progress meter + product recommendations
 * ─────────────────────────────────────────────────────────────────────────────
 * Power the custom cart template (woocommerce/cart/cart.php): the
 * "Spend only $X more to reach free shipping" meter and the "You'll Also Love"
 * cross-sell row.
 */

/**
 * The cart subtotal that unlocks free shipping.
 *
 * Reads the minimum order amount from any enabled WooCommerce "Free shipping"
 * method (lowest wins, across every zone incl. "Rest of the world"). Falls back
 * to $80 — the figure in the site's top banner — when none is configured.
 * Override with the `kjc_free_shipping_threshold` filter (return a number).
 *
 * @return float
 */
function kjc_get_free_shipping_threshold() {
	$override = apply_filters( 'kjc_free_shipping_threshold', null );
	if ( is_numeric( $override ) ) {
		return (float) $override;
	}

	$threshold = 0.0;

	if ( class_exists( 'WC_Shipping_Zones' ) ) {
		// Zone 0 is the catch-all "Rest of the world"; the rest are admin-defined.
		$zone_ids = array( 0 );
		foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
			if ( isset( $zone['id'] ) ) {
				$zone_ids[] = (int) $zone['id'];
			}
		}

		foreach ( $zone_ids as $zone_id ) {
			$zone = WC_Shipping_Zones::get_zone( $zone_id );
			if ( ! $zone ) {
				continue;
			}
			foreach ( $zone->get_shipping_methods( true ) as $method ) {
				if ( 'free_shipping' !== $method->id ) {
					continue;
				}
				$requires = isset( $method->requires ) ? $method->requires : $method->get_option( 'requires' );
				// Only thresholds count; "n/a" or coupon-only free shipping has no spend target.
				if ( ! in_array( $requires, array( 'min_amount', 'either', 'both' ), true ) ) {
					continue;
				}
				$amount = (float) ( isset( $method->min_amount ) ? $method->min_amount : $method->get_option( 'min_amount' ) );
				if ( $amount > 0 && ( 0.0 === $threshold || $amount < $threshold ) ) {
					$threshold = $amount;
				}
			}
		}
	}

	if ( $threshold <= 0 ) {
		$threshold = 80.0; // Matches the "Free … Shipping on orders $80+" banner.
	}

	return (float) $threshold;
}

/**
 * How much more the customer must spend to unlock free shipping.
 * Compared against the cart subtotal (items only, excl. shipping/tax).
 * Returns 0 once the threshold is met.
 *
 * @return float
 */
function kjc_get_free_shipping_remaining() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0.0;
	}
	$remaining = kjc_get_free_shipping_threshold() - (float) WC()->cart->get_subtotal();
	return $remaining > 0 ? $remaining : 0.0;
}

/**
 * Product IDs to recommend in the cart's "You'll Also Love" row.
 *
 * Order of preference:
 *   1. Cross-sells assigned to cart items (Product data → Linked Products →
 *      Cross-sells) — WooCommerce's native "pairs well with" field.
 *   2. Best-sellers (most units sold) to fill any remaining slots.
 *   3. Newest products, as a final fallback for a store with no sales yet.
 *
 * Products already in the cart are always excluded.
 *
 * @param int $limit Maximum number of recommendations.
 * @return int[]
 */
function kjc_get_cart_recommendations( $limit = 3 ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return array();
	}

	$limit   = max( 1, (int) $limit );
	$in_cart = array();
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		$in_cart[] = (int) $cart_item['product_id'];
	}

	$ids = array();

	// 1) Native cross-sells.
	foreach ( WC()->cart->get_cross_sells() as $cross_id ) {
		$cross_id = (int) $cross_id;
		if ( in_array( $cross_id, $in_cart, true ) || in_array( $cross_id, $ids, true ) ) {
			continue;
		}
		$product = wc_get_product( $cross_id );
		if ( $product && $product->is_visible() && $product->is_in_stock() ) {
			$ids[] = $cross_id;
		}
		if ( count( $ids ) >= $limit ) {
			return array_slice( $ids, 0, $limit );
		}
	}

	// 2) then 3) Top up with best-sellers, then newest.
	$fill_queries = array(
		array( 'orderby' => 'meta_value_num', 'meta_key' => 'total_sales', 'order' => 'DESC' ),
		array( 'orderby' => 'date', 'order' => 'DESC' ),
	);

	foreach ( $fill_queries as $query_args ) {
		if ( count( $ids ) >= $limit ) {
			break;
		}
		$exclude  = array_merge( $in_cart, $ids );
		$products = wc_get_products( array_merge( array(
			'status'       => 'publish',
			'limit'        => $limit + count( $exclude ), // grab extra so exclusions still leave enough
			'stock_status' => 'instock',
			'visibility'   => 'catalog',
			'exclude'      => $exclude,
			'return'       => 'ids',
		), $query_args ) );

		foreach ( $products as $pid ) {
			$pid = (int) $pid;
			if ( in_array( $pid, $in_cart, true ) || in_array( $pid, $ids, true ) ) {
				continue;
			}
			$ids[] = $pid;
			if ( count( $ids ) >= $limit ) {
				break;
			}
		}
	}

	return array_slice( $ids, 0, $limit );
}

/**
 * Cart total for on-page display: items after discounts (+ fees), EXCLUDING
 * shipping and tax. Keeps the cart's "Total" free of "calculated at checkout"
 * estimates, so Subtotal − Coupon visibly equals Total. Shipping and tax are
 * still charged — they're just finalised on the checkout page.
 *
 * @return float
 */
function kjc_get_cart_display_total() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0.0;
	}
	// get_cart_contents_total() = line totals AFTER coupons, excl. tax/shipping/fees.
	return (float) WC()->cart->get_cart_contents_total() + (float) WC()->cart->get_fee_total();
}

/**
 * Render the free-shipping progress bar (cart / product / shop / checkout).
 *
 * Shows how close the cart is to the free-shipping threshold, in three states:
 * empty cart (teaser), partway (nudge) and reached (celebration). Outputs
 * nothing if WooCommerce/cart is unavailable or no threshold is configured.
 * Styles live in css/main.css (.kjc-shipbar).
 *
 * @param string $context Where it renders (cart|product|shop|checkout); becomes a
 *                        modifier class and is passed to the disable filter.
 */
function kjc_render_free_shipping_bar( $context = '' ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	// Let callers suppress the bar in a given context if needed.
	if ( ! apply_filters( 'kjc_show_free_shipping_bar', true, $context ) ) {
		return;
	}

	$threshold = kjc_get_free_shipping_threshold();
	if ( $threshold <= 0 ) {
		return;
	}

	$subtotal  = (float) WC()->cart->get_subtotal();
	$remaining = max( 0.0, $threshold - $subtotal );
	$reached   = $remaining <= 0;
	$percent   = $reached ? 100 : min( 100, max( 4, ( $subtotal / $threshold ) * 100 ) );

	if ( $reached ) {
		$message = '🎉 You’ve unlocked <strong>FREE shipping!</strong>';
	} elseif ( $subtotal <= 0 ) {
		$message = 'Enjoy <strong>free shipping</strong> on orders over <strong>' . wc_price( $threshold ) . '</strong>!';
	} else {
		$message = 'Spend only <strong>' . wc_price( $remaining ) . '</strong> more to reach <strong>free shipping!</strong>';
	}

	$classes = 'kjc-shipbar';
	if ( $reached ) {
		$classes .= ' is-reached';
	}
	if ( $context ) {
		$classes .= ' kjc-shipbar--' . sanitize_html_class( $context );
	}

	$truck = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="1.8"/><circle cx="18.5" cy="18.5" r="1.8"/></svg>';
	$check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<p class="kjc-shipbar__msg"><?php echo wp_kses_post( $message ); ?></p>
		<div class="kjc-shipbar__track">
			<div class="kjc-shipbar__fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
			<span class="kjc-shipbar__goal" aria-hidden="true"><?php echo $reached ? $check : $truck; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG ?></span>
		</div>
		<div class="kjc-shipbar__labels"><span>Free Shipping</span></div>
	</div>
	<?php
}

// Surface the free-shipping bar on product pages (above add-to-cart) and the
// shop/archive grid. Cart and checkout call kjc_render_free_shipping_bar()
// directly from their templates.
add_action( 'woocommerce_before_add_to_cart_form', function () { kjc_render_free_shipping_bar( 'product' ); }, 5 );
add_action( 'woocommerce_before_shop_loop', function () { kjc_render_free_shipping_bar( 'shop' ); }, 5 );

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


/**
 * ─────────────────────────────────────────────────────────────────────────────
 * WELCOME DISCOUNT + EMAIL CAPTURE
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * Three connected pieces:
 *
 *   1. AUTO-APPLY  — When a shopper claims the popup discount (or ticks the
 *      checkout opt-in box), the welcome coupon is saved to their cart/session
 *      so it is already in the cart total before checkout — including express
 *      (Apple Pay / Google Pay) checkouts, which have no coupon field.
 *
 *   2. CHECKOUT OPT-IN — A pre-checked checkbox at the very top of checkout
 *      applies the same coupon and, once the order is placed, sends the buyer's
 *      EMAIL ONLY to the Google Sheet mailing list (phone is collected only via
 *      the manual popup sign-up).
 *
 *   3. SERVER-SIDE SHEET POST — Capture runs on the order, so it works for
 *      express checkout too (there is no front-end form submit to hook into).
 *
 * NOTE: The discount only does something if a WooCommerce coupon with the code
 *       below actually exists (WooCommerce → Marketing → Coupons). If it has
 *       not been created yet, nothing breaks — the code simply has no effect.
 * ─────────────────────────────────────────────────────────────────────────────
 */

if ( ! defined( 'KJC_COUPON_CODE' ) ) {
    define( 'KJC_COUPON_CODE', 'JesusIsKing15' );
}
if ( ! defined( 'KJC_SHEET_URL' ) ) {
    define( 'KJC_SHEET_URL', 'https://script.google.com/macros/s/AKfycbz-uCZoMRAk0Y3asGnqiwpu3CxRy0PzIvg30eZzT6OfVaJH_VTEk7sPvnZKnQ6_r-Ba/exec' );
}

/**
 * Apply the welcome coupon to the cart — but only if it really exists as a
 * WooCommerce coupon. This avoids a "coupon does not exist" error notice when
 * the code has not been set up in the store yet.
 */
function kjc_apply_welcome_coupon() {
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
        return;
    }
    $code = wc_format_coupon_code( KJC_COUPON_CODE );
    if ( ! $code || WC()->cart->has_discount( $code ) ) {
        return;
    }
    $coupon = new WC_Coupon( $code );
    if ( ! $coupon->get_id() ) {
        return; // Coupon not created in WooCommerce — do nothing.
    }
    WC()->cart->apply_coupon( $code );
}

/**
 * Should the welcome discount be on the cart for this shopper?
 * True when they claimed it via the popup (cookie / session) or ticked the
 * checkout opt-in box. An explicit untick at checkout wins for the session.
 */
function kjc_wants_welcome_coupon() {
    if ( function_exists( 'WC' ) && WC()->session && 'no' === WC()->session->get( 'kjc_checkout_optin' ) ) {
        return false;
    }
    if ( isset( $_COOKIE['kjc_pending_coupon'] ) && '1' === $_COOKIE['kjc_pending_coupon'] ) {
        return true;
    }
    if ( function_exists( 'WC' ) && WC()->session ) {
        if ( 'yes' === WC()->session->get( 'kjc_pending_coupon' ) ) {
            return true;
        }
        if ( 'yes' === WC()->session->get( 'kjc_checkout_optin' ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Auto-apply the welcome coupon whenever the cart is loaded or an item is
 * added, so it is baked into the total before an express payment sheet opens.
 */
function kjc_maybe_auto_apply_coupon() {
    if ( is_admin() && ! wp_doing_ajax() ) {
        return;
    }
    if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
        return;
    }
    if ( ! kjc_wants_welcome_coupon() ) {
        return;
    }
    kjc_apply_welcome_coupon();
}
add_action( 'woocommerce_cart_loaded_from_session', 'kjc_maybe_auto_apply_coupon', 20 );
add_action( 'woocommerce_add_to_cart', 'kjc_maybe_auto_apply_coupon', 20 );

/**
 * Popup "Claim 15% off": remember the discount for this shopper and apply it
 * now if they already have items in the cart.
 */
add_action( 'wp_ajax_kjc_apply_coupon', 'kjc_ajax_apply_coupon' );
add_action( 'wp_ajax_nopriv_kjc_apply_coupon', 'kjc_ajax_apply_coupon' );
function kjc_ajax_apply_coupon() {
    check_ajax_referer( 'kjc_coupon', 'nonce' );
    if ( function_exists( 'WC' ) && WC()->session ) {
        WC()->session->set( 'kjc_pending_coupon', 'yes' );
        // A fresh popup claim re-enables the discount even if it was unticked.
        WC()->session->set( 'kjc_checkout_optin', null );
    }
    kjc_apply_welcome_coupon();
    wp_send_json_success();
}

/**
 * Checkout opt-in checkbox toggled: apply or remove the coupon and remember the
 * choice so the email can be captured after the order is placed.
 */
add_action( 'wp_ajax_kjc_checkout_optin', 'kjc_ajax_checkout_optin' );
add_action( 'wp_ajax_nopriv_kjc_checkout_optin', 'kjc_ajax_checkout_optin' );
function kjc_ajax_checkout_optin() {
    check_ajax_referer( 'kjc_coupon', 'nonce' );
    $optin = isset( $_POST['optin'] ) && 'yes' === $_POST['optin'];

    if ( function_exists( 'WC' ) && WC()->session ) {
        WC()->session->set( 'kjc_checkout_optin', $optin ? 'yes' : 'no' );
    }
    if ( function_exists( 'WC' ) && WC()->cart ) {
        if ( $optin ) {
            kjc_apply_welcome_coupon();
        } else {
            $code = wc_format_coupon_code( KJC_COUPON_CODE );
            if ( $code && WC()->cart->has_discount( $code ) ) {
                WC()->cart->remove_coupon( $code );
            }
        }
    }
    wp_send_json_success();
}

/**
 * Pre-checked opt-in checkbox at the very top of checkout, just above the
 * billing details. On mobile the customer-details column stacks first, so this
 * is the first thing the shopper sees.
 */
add_action( 'woocommerce_checkout_before_customer_details', 'kjc_render_checkout_optin' );
function kjc_render_checkout_optin() {
    // Pre-checked by default; respect an earlier untick this session.
    $checked = true;
    if ( function_exists( 'WC' ) && WC()->session && 'no' === WC()->session->get( 'kjc_checkout_optin' ) ) {
        $checked = false;
    }
    ?>
    <div class="kjc-checkout-optin">
        <label class="kjc-optin-label">
            <input type="checkbox" id="kjc_optin" name="kjc_optin" value="yes" <?php checked( $checked ); ?>>
            <span>Email me exclusive offers &amp; apply my <strong>15% discount</strong></span>
        </label>
        <p class="kjc-optin-note">
            By checking this box you agree to receive marketing emails from King Jesus Clothing. You can unsubscribe at any time.
        </p>
    </div>
    <?php
}

/**
 * After an order is placed, if the shopper opted in (and did not already sign
 * up through the popup), send their email to the Google Sheet mailing list.
 * Hooked for both classic checkout and the Store API (blocks / express), so it
 * captures express (Apple/Google Pay) orders too. Email only.
 */
add_action( 'woocommerce_checkout_order_processed', 'kjc_capture_checkout_optin_email', 20, 1 );
add_action( 'woocommerce_store_api_checkout_order_processed', 'kjc_capture_checkout_optin_email', 20, 1 );
function kjc_capture_checkout_optin_email( $order ) {
    if ( ! $order instanceof WC_Order ) {
        $order = wc_get_order( $order );
    }
    if ( ! $order || $order->get_meta( '_kjc_optin_sent' ) ) {
        return;
    }

    // Determine opt-in. Classic checkout posts the checkbox value (absent =
    // unchecked); express / Store API has no form, so fall back to the session
    // flag, treating "unknown" as opted-in because the box is pre-checked.
    $is_classic_submit = isset( $_POST['woocommerce-process-checkout-nonce'] ) || isset( $_POST['billing_email'] );
    if ( isset( $_POST['kjc_optin'] ) ) {
        $opted_in = ( 'yes' === sanitize_text_field( wp_unslash( $_POST['kjc_optin'] ) ) );
    } elseif ( $is_classic_submit ) {
        $opted_in = false; // Classic submit with the box unchecked.
    } else {
        $flag     = ( function_exists( 'WC' ) && WC()->session ) ? WC()->session->get( 'kjc_checkout_optin' ) : '';
        $opted_in = ( 'no' !== $flag );
    }
    if ( ! $opted_in ) {
        return;
    }

    // If they already signed up through the popup, their details (incl. phone)
    // were captured there — skip to avoid a duplicate checkout-only entry.
    if ( isset( $_COOKIE['kjc_pending_coupon'] ) && '1' === $_COOKIE['kjc_pending_coupon'] ) {
        return;
    }

    $email = $order->get_billing_email();
    if ( ! $email ) {
        return;
    }

    kjc_post_to_sheet( array(
        'email'  => $email,
        'source' => 'checkout-optin',
    ) );

    $order->update_meta_data( '_kjc_optin_sent', 'yes' );
    $order->save();
}

/**
 * Fire-and-forget POST to the Google Sheet web app. Non-blocking so it never
 * holds up order processing.
 */
function kjc_post_to_sheet( array $payload ) {
    if ( ! defined( 'KJC_SHEET_URL' ) || ! KJC_SHEET_URL ) {
        return;
    }
    if ( empty( $payload['timestamp'] ) ) {
        $payload['timestamp'] = gmdate( 'c' );
    }
    wp_remote_post( KJC_SHEET_URL, array(
        'timeout'  => 8,
        'blocking' => false,
        'headers'  => array( 'Content-Type' => 'application/json' ),
        'body'     => wp_json_encode( $payload ),
    ) );
}
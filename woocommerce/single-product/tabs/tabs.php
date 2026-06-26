<?php
/**
 * Single Product tabs — rendered as collapsible dropdown (accordion) panels
 * instead of horizontal tabs.
 *
 * Override: woocommerce/single-product/tabs/tabs.php
 *
 * Uses native <details>/<summary> so it works without JS. A small script
 * (in single-product.php) closes the other panels when one is opened, so it
 * behaves like a one-at-a-time accordion. Deliberately avoids WooCommerce's
 * .woocommerce-tabs / .wc-tab / .panel classes so core's tab JS leaves it alone.
 *
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
	<div class="kjc-accordion" id="kjc-product-accordion">
		<?php $kjc_i = 0; foreach ( $product_tabs as $key => $product_tab ) : ?>
			<details class="kjc-accordion-item kjc-accordion-item--<?php echo esc_attr( $key ); ?>"<?php echo 0 === $kjc_i ? ' open' : ''; ?>>
				<summary class="kjc-accordion-header">
					<span class="kjc-accordion-title">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
					</span>
					<span class="kjc-accordion-icon" aria-hidden="true"></span>
				</summary>
				<div class="kjc-accordion-panel" id="kjc-tab-<?php echo esc_attr( $key ); ?>">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
			</details>
		<?php $kjc_i++; endforeach; ?>
	</div>
<?php endif; ?>

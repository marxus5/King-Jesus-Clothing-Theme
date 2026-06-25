<?php
/**
 * Cart Page Template
 * Override: woocommerce/cart/cart.php
 *
 * Mobile-first redesign for King Jesus Clothing:
 *   - Free-shipping progress meter ("Spend only $X more …")
 *   - Clean single-column item cards (no more squished mobile grid)
 *   - "You'll Also Love" row (cross-sells, falling back to best-sellers)
 *   - Theme-coloured totals + checkout summary
 *
 * Helpers live in functions.php:
 *   kjc_get_free_shipping_threshold(), kjc_get_free_shipping_remaining(),
 *   kjc_get_cart_recommendations().
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<style>
/* ── Cart layout (scoped to .kjc-cart) ──────────────────────────────────────
   Colours pull from the theme vars in css/main.css with hard-coded fallbacks. */
.kjc-cart {
    --kjc-deep:  var(--red-deep, #7A0E1A);
    --kjc-red:   var(--red, #CE202F);
    --kjc-ink:   var(--black, #1D1D1D);
    --kjc-muted: #6b7280;
    --kjc-line:  #ececec;
    --kjc-soft:  #f6f6f7;
    --kjc-grad:  linear-gradient(135deg, #f34040 0%, #830b15 100%);

    max-width: 600px;
    margin: 0 auto;
    padding: 2rem 1.1rem 3.5rem;
    font-family: var(--font, 'Segoe UI', system-ui, -apple-system, sans-serif);
    color: var(--kjc-ink);
    box-sizing: border-box;
}
.kjc-cart *, .kjc-cart *::before, .kjc-cart *::after { box-sizing: border-box; }

.kjc-cart__title {
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: 0.01em;
    margin: 0 0 1.25rem;
    text-align: center;
    color: var(--kjc-ink);
}

/* Free-shipping bar styles live in css/main.css (.kjc-shipbar), shared with the
   product, shop and checkout pages. */

/* ── Item cards ──────────────────────────────────────────────────────────── */
.kjc-cart__items { margin: 0 0 1.5rem; }

.kjc-item {
    display: grid;
    grid-template-columns: 88px 1fr;
    gap: 1rem;
    padding: 1.1rem 0;
    border-bottom: 1px solid var(--kjc-line);
    align-items: start;
}
.kjc-item:first-child { padding-top: 0; }

.kjc-item__img {
    display: block;
    width: 88px;
    height: 110px;
    border-radius: 10px;
    overflow: hidden;
    background: var(--kjc-soft);
}
.kjc-item__img img { width: 100%; height: 100%; object-fit: cover; display: block; }

.kjc-item__body { min-width: 0; }
.kjc-item__top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
}
.kjc-item__title {
    font-size: 0.98rem;
    font-weight: 700;
    line-height: 1.3;
    margin: 0;
    color: var(--kjc-ink);
}
.kjc-item__title a { color: inherit; text-decoration: none; }
.kjc-item__title a:hover { color: var(--kjc-deep); }

.kjc-item__price {
    font-size: 0.98rem;
    font-weight: 800;
    color: var(--kjc-ink);
    white-space: nowrap;
}
.kjc-item__price del { color: var(--kjc-muted); font-weight: 500; margin-right: 0.3rem; }
.kjc-item__price ins { text-decoration: none; color: var(--kjc-deep); }

.kjc-item__meta {
    margin: 0.3rem 0 0;
    font-size: 0.85rem;
    color: var(--kjc-muted);
}
.kjc-item__meta p { margin: 0.1rem 0; }
.kjc-item__meta dl { margin: 0.1rem 0; }
.kjc-item__meta dt, .kjc-item__meta dd { display: inline; margin: 0; }
.kjc-item__meta dt { font-weight: 600; }
.kjc-item__meta dd::after { content: ''; display: block; }

.kjc-item__controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: 0.75rem;
}

/* Quantity stepper */
.kjc-qty {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.kjc-qty__btn {
    width: 34px;
    height: 34px;
    border: none;
    background: #fff;
    color: var(--kjc-ink);
    font-size: 1.15rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
}
.kjc-qty__btn:hover { background: var(--kjc-deep); color: #fff; }
.kjc-qty .quantity { margin: 0; }
.kjc-qty .quantity input.qty,
.kjc-qty__static {
    width: 38px;
    height: 34px;
    text-align: center;
    border: none;
    border-left: 1px solid #ededed;
    border-right: 1px solid #ededed;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--kjc-ink);
    background: #fff;
    padding: 0;
    -moz-appearance: textfield;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kjc-qty .quantity input.qty:focus { outline: none; }
.kjc-qty .quantity input.qty::-webkit-outer-spin-button,
.kjc-qty .quantity input.qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.kjc-item__remove {
    background: none;
    border: none;
    padding: 0;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--kjc-muted);
    text-decoration: none;
    cursor: pointer;
    transition: color 0.15s;
}
.kjc-item__remove:hover { color: var(--kjc-red); text-decoration: underline; }

/* ── Recommendations ─────────────────────────────────────────────────────── */
.kjc-recs { margin: 0 0 1.75rem; }
.kjc-recs__title {
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--kjc-muted);
    margin: 0 0 0.9rem;
}
.kjc-recs__list { display: flex; flex-direction: column; gap: 0.6rem; }

.kjc-rec {
    display: grid;
    grid-template-columns: 56px 1fr auto;
    align-items: center;
    gap: 0.85rem;
    background: var(--kjc-soft);
    border-radius: 12px;
    padding: 0.6rem 0.75rem;
}
.kjc-rec__img {
    display: block;
    width: 56px;
    height: 64px;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.kjc-rec__img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.kjc-rec__info { min-width: 0; }
.kjc-rec__name {
    display: block;
    font-size: 0.86rem;
    font-weight: 700;
    line-height: 1.25;
    color: var(--kjc-ink);
    text-decoration: none;
}
.kjc-rec__name:hover { color: var(--kjc-deep); }
.kjc-rec__price { display: block; margin-top: 0.2rem; font-size: 0.85rem; font-weight: 700; color: var(--kjc-ink); }
.kjc-rec__price del { color: var(--kjc-muted); font-weight: 500; margin-right: 0.25rem; }
.kjc-rec__price ins { text-decoration: none; color: var(--kjc-deep); }
.kjc-rec__add {
    flex-shrink: 0;
    background: var(--kjc-deep);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 0.62rem 0.95rem;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background 0.15s, transform 0.15s;
}
.kjc-rec__add:hover { background: #5e0a13; color: #fff; transform: translateY(-1px); }

/* ── Summary / checkout ──────────────────────────────────────────────────── */
.kjc-summary {
    background: #fff;
    border: 1px solid var(--kjc-line);
    border-radius: 14px;
    padding: 1.25rem 1.25rem 1.4rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.kjc-summary__row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 0.95rem;
    color: var(--kjc-muted);
    padding: 0.25rem 0;
}
.kjc-summary__row.is-total {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--kjc-ink);
    padding-top: 0.7rem;
    margin-top: 0.4rem;
    border-top: 1px solid var(--kjc-line);
}
.kjc-summary__row.is-total span:last-child { color: var(--kjc-deep); }
.kjc-summary__note {
    font-size: 0.8rem;
    color: var(--kjc-muted);
    text-align: center;
    margin: 0.5rem 0 0;
}

.kjc-checkout {
    display: block;
    width: 100%;
    margin-top: 1rem;
    padding: 1.05rem 1.5rem;
    background: var(--kjc-grad);
    color: #fff;
    border: none;
    border-radius: 999px;
    font-size: 1.05rem;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(131,11,21,0.28);
    transition: transform 0.15s, box-shadow 0.15s;
}
.kjc-checkout:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(131,11,21,0.36); color: #fff; }

.kjc-continue {
    display: block;
    text-align: center;
    margin-top: 0.9rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--kjc-muted);
    text-decoration: none;
}
.kjc-continue:hover { color: var(--kjc-deep); text-decoration: underline; }

/* Desktop polish — keep it a tidy centred column rather than stretching wide. */
@media (min-width: 700px) {
    .kjc-cart { padding-top: 2.75rem; }
    .kjc-cart__title { font-size: 2rem; }
    .kjc-item__title, .kjc-item__price { font-size: 1.05rem; }
}
</style>

<div class="kjc-cart">

    <h1 class="kjc-cart__title">Your Cart</h1>

    <!-- ── Free-shipping progress bar (shared component, see functions.php) ── -->
    <?php kjc_render_free_shipping_bar( 'cart' ); ?>

    <!-- ── Cart items ── -->
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

        <div class="kjc-cart__items">
            <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                    $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                    $item_data         = wc_get_formatted_cart_item_data( $cart_item, true );
                    ?>
                    <div class="kjc-item">

                        <!-- Thumbnail -->
                        <?php if ( $product_permalink ) : ?>
                            <a class="kjc-item__img" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; ?></a>
                        <?php else : ?>
                            <span class="kjc-item__img"><?php echo $thumbnail; ?></span>
                        <?php endif; ?>

                        <!-- Details -->
                        <div class="kjc-item__body">
                            <div class="kjc-item__top">
                                <h3 class="kjc-item__title">
                                    <?php if ( $product_permalink ) : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
                                    <?php else : ?>
                                        <?php echo wp_kses_post( $product_name ); ?>
                                    <?php endif; ?>
                                </h3>
                                <span class="kjc-item__price">
                                    <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                                </span>
                            </div>

                            <?php if ( $item_data ) : ?>
                                <div class="kjc-item__meta"><?php echo wp_kses_post( $item_data ); ?></div>
                            <?php endif; ?>

                            <div class="kjc-item__controls">
                                <?php if ( $_product->is_sold_individually() ) : ?>
                                    <span class="kjc-qty">
                                        <span class="kjc-qty__static">1</span>
                                        <input type="hidden" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="1" />
                                    </span>
                                <?php else : ?>
                                    <span class="kjc-qty">
                                        <button type="button" class="kjc-qty__btn" data-dir="-1" aria-label="Decrease quantity">&minus;</button>
                                        <?php
                                        echo woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '1',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                        ?>
                                        <button type="button" class="kjc-qty__btn" data-dir="1" aria-label="Increase quantity">+</button>
                                    </span>
                                <?php endif; ?>

                                <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                                   class="kjc-item__remove"
                                   aria-label="<?php echo esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ); ?>">
                                    <?php esc_html_e( 'Remove', 'woocommerce' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Hidden update-cart button (auto-clicked by JS when a quantity changes) -->
        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display:none;" disabled>
            <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
        </button>
    </form>

    <!-- ── Recommendations: cross-sells, falling back to best-sellers ── -->
    <?php
    $kjc_recs = function_exists( 'kjc_get_cart_recommendations' ) ? kjc_get_cart_recommendations( 3 ) : array();
    if ( ! empty( $kjc_recs ) ) : ?>
        <section class="kjc-recs">
            <h2 class="kjc-recs__title">You’ll Also Love</h2>
            <div class="kjc-recs__list">
                <?php foreach ( $kjc_recs as $kjc_rec_id ) :
                    $kjc_rec = wc_get_product( $kjc_rec_id );
                    if ( ! $kjc_rec ) {
                        continue;
                    }
                    // Simple products add straight to the cart; variable products need a
                    // size chosen, so add_to_cart_url() sends them to the product page.
                    $kjc_rec_url  = $kjc_rec->get_permalink();
                    $kjc_rec_add  = $kjc_rec->add_to_cart_url();
                    ?>
                    <div class="kjc-rec">
                        <a class="kjc-rec__img" href="<?php echo esc_url( $kjc_rec_url ); ?>"><?php echo $kjc_rec->get_image( 'woocommerce_thumbnail' ); ?></a>
                        <div class="kjc-rec__info">
                            <a class="kjc-rec__name" href="<?php echo esc_url( $kjc_rec_url ); ?>"><?php echo esc_html( $kjc_rec->get_name() ); ?></a>
                            <span class="kjc-rec__price"><?php echo wp_kses_post( $kjc_rec->get_price_html() ); ?></span>
                        </div>
                        <a class="kjc-rec__add"
                           href="<?php echo esc_url( $kjc_rec_add ); ?>"
                           data-product_id="<?php echo esc_attr( $kjc_rec_id ); ?>"
                           aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'woocommerce' ), $kjc_rec->get_name() ) ); ?>">
                            Add +
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- ── Totals + checkout ── -->
    <div class="kjc-summary">
        <div class="kjc-summary__row">
            <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="kjc-summary__row">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="kjc-summary__row">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <div class="kjc-summary__row is-total">
            <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
            <span><?php echo wp_kses_post( wc_price( kjc_get_cart_display_total() ) ); ?></span>
        </div>

        <p class="kjc-summary__note"><?php esc_html_e( 'Shipping &amp; taxes calculated at checkout.', 'woocommerce' ); ?></p>

        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="kjc-checkout">
            <?php esc_html_e( 'Proceed to Checkout', 'woocommerce' ); ?>
        </a>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="kjc-continue">
            <?php esc_html_e( '← Continue shopping', 'woocommerce' ); ?>
        </a>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.kjc-cart .woocommerce-cart-form');

    // Submit the (hidden) "Update cart" button so WooCommerce recalculates.
    function submitUpdate() {
        var btn = form && form.querySelector('[name="update_cart"]');
        if (btn) { btn.disabled = false; btn.click(); }
    }

    // − / + quantity steppers.
    document.querySelectorAll('.kjc-qty__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input.qty');
            if (!input) { return; }
            var step = parseFloat(input.getAttribute('step')) || 1;
            var min  = parseFloat(input.getAttribute('min'));
            if (isNaN(min)) { min = 1; }
            var maxAttr = input.getAttribute('max');
            var max = (maxAttr === '' || maxAttr === null) ? Infinity : parseFloat(maxAttr);
            var val = parseFloat(input.value) || min;
            val += (parseInt(btn.getAttribute('data-dir'), 10) || 0) * step;
            if (val < min) { val = min; }
            if (val > max) { val = max; }
            input.value = val;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });

    // Auto-update the cart whenever a quantity changes.
    document.querySelectorAll('.kjc-qty input.qty').forEach(function (input) {
        input.addEventListener('change', submitUpdate);
    });

    // Coupon "[Remove]" link: WooCommerce removes it via AJAX, but that updates
    // its own fragments — not this custom totals box — so the coupon line would
    // linger until a reload. Intercept in the capture phase to skip WC's AJAX
    // and do a full navigation to the remove URL instead, refreshing the page.
    document.addEventListener('click', function (e) {
        var link = e.target.closest('.woocommerce-remove-coupon');
        if (!link) { return; }
        e.preventDefault();
        e.stopImmediatePropagation();
        var href = link.getAttribute('href');
        window.location.href = href ? href : window.location.href;
    }, true);
});
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>

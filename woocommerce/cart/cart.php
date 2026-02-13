<?php
error_log('Custom cart template is loaded!');
/**
 * Cart Page Template
 * Override: woocommerce/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

// Remove default WooCommerce cart messages
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message' );

do_action( 'woocommerce_before_cart' ); ?>

<style>
.custom-cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.custom-cart-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: #1f2937;
    text-align: center;
}

.custom-cart-items {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.custom-cart-item {
    display: grid;
    grid-template-columns: 120px 1fr 150px 50px;
    gap: 2rem;
    padding: 2rem 0;
    border-bottom: 1px solid #f3f4f6;
    align-items: center;
}

.custom-cart-item:last-child {
    border-bottom: none;
}

.custom-cart-image {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
}

.custom-cart-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.custom-cart-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.custom-product-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.custom-product-title a {
    color: #1f2937;
    text-decoration: none;
}

.custom-product-title a:hover {
    color: #667eea;
}

.custom-detail-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: #4b5563;
}

.custom-detail-label {
    font-weight: 600;
    color: #1f2937;
    min-width: 80px;
}

.custom-detail-row .quantity {
    display: inline-flex;
    align-items: center;
}

.custom-detail-row .quantity input qty {
    width: 70px;
    padding: 0.5rem;
    text-align: center;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 600;
}

.custom-detail-row .quantity input qty:focus {
    outline: none;
    border-color: #667eea;
}

.custom-item-price {
    font-size: 1rem;
    font-weight: 600;
    color: #667eea;
}

.custom-variation {
    font-size: 0.9rem;
    color: #6b7280;
}

.custom-cart-total {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    text-align: right;
}

.custom-cart-remove {
    background: #fee2e2;
    color: #ef4444;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.5rem;
    font-weight: bold;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    text-decoration: none;
}

.custom-cart-remove:hover {
    background: #ef4444;
    color: white;
    transform: scale(1.1);
}

.custom-cart-totals {
    max-width: 600px;
    margin: 0 auto;
    background: #f9fafb;
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.custom-cart-totals h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1f2937;
    text-align: center;
}

.custom-totals-row {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e7eb;
    font-size: 1.125rem;
}

.custom-totals-row.total {
    font-size: 1.75rem;
    font-weight: 700;
    color: #667eea;
    border-bottom: none;
    padding-top: 1.5rem;
}

.custom-checkout-button {
    display: block;
    width: 100%;
    max-width: 100%;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    text-decoration: none;
    margin: 1.5rem 0 0 0;
}

.custom-checkout-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.custom-empty-cart {
    text-align: center;
    padding: 4rem 2rem;
}

.custom-empty-cart-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.7;
}

.custom-empty-cart p {
    font-size: 1.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.custom-empty-cart-subtext {
    font-size: 1rem;
    color: #9ca3af;
    margin-bottom: 2rem;
}

.custom-continue-shopping {
    display: inline-block;
    padding: 1rem 2.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.custom-continue-shopping:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Empty cart featured products section */
.empty-cart-products-section {
    margin-top: 4rem;
    padding-top: 4rem;
    border-top: 2px solid #e5e7eb;
}

.empty-cart-products-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    text-align: center;
    margin-bottom: 3rem;
}

.empty-cart-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.empty-cart-product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
}

.empty-cart-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.empty-cart-product-image {
    width: 100%;
    height: 220px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.empty-cart-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.empty-cart-product-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.empty-cart-product-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.empty-cart-product-price {
    font-size: 1.375rem;
    font-weight: 700;
    color: #667eea;
    margin-top: auto;
}

@media (max-width: 768px) {
    .custom-cart-item {
        grid-template-columns: 80px 1fr;
        gap: 1rem;
    }
    
    .custom-cart-image {
        width: 80px;
        height: 80px;
        grid-row: 1 / 3;
    }
    
    .custom-cart-details {
        grid-column: 2;
    }
    
    .custom-cart-total {
        grid-column: 2;
        text-align: left;
        font-size: 1.25rem;
        margin-top: 0.5rem;
    }
    
    .custom-cart-remove {
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle remove button clicks: use WooCommerce AJAX to remove items
    const removeButtons = document.querySelectorAll('.custom-cart-remove');
    removeButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const cartItemKey = this.getAttribute('data-cart-item-key');
            const removeUrl = this.getAttribute('href');
            
            if (removeUrl) {
                // Use fetch to trigger WooCommerce remove via AJAX
                fetch(removeUrl, { method: 'GET' })
                    .then(response => response.text())
                    .then(() => {
                        // Redirect to cart page to refresh
                        window.location.reload();
                    })
                    .catch(err => {
                        console.error('Error removing item:', err);
                        // Fallback: reload page
                        window.location.reload();
                    });
            }
        });
    });

    // Clear Cart button handler: use WooCommerce AJAX to remove all items
    const clearCartBtn = document.querySelector('.custom-clear-cart-button');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            const removeButtons = document.querySelectorAll('.custom-cart-remove');
            let removed = 0;
            let total = removeButtons.length;
            
            if (total === 0) {
                window.location.reload();
                return;
            }
            
            removeButtons.forEach(function(btn) {
                const removeUrl = btn.getAttribute('href');
                if (removeUrl) {
                    fetch(removeUrl, { method: 'GET' })
                        .then(() => {
                            removed++;
                            if (removed === total) {
                                window.location.reload();
                            }
                        })
                        .catch(() => {
                            removed++;
                            if (removed === total) {
                                window.location.reload();
                            }
                        });
                }
            });
        });
    }

    //Auto-update cart when quantity changes
    const qtyInputs = document.querySelectorAll('.custom-detail-row .qty');
    qtyInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const updateButton = document.querySelector('[name="update_cart"]');
            if (updateButton) {
                updateButton.disabled = false;
                updateButton.click();
            }
        });
    });

});
</script>

<div class="custom-cart-container">
    <h1 class="custom-cart-title">Shopping Cart</h1>

    

    <?php if ( WC()->cart->is_empty() ) : ?>
        
          <div class="custom-empty-cart">
            <div class="custom-empty-cart-icon">🛒</div>
            <p><?php esc_html_e( 'Your cart is empty', 'woocommerce' ); ?></p>
            <div class="custom-empty-cart-subtext"><?php esc_html_e( 'Start shopping to add items to your cart', 'woocommerce' ); ?></div>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-continue-shopping">
                Continue Shopping
            </a>
        </div>

        <!-- Featured Products Below Empty Cart -->
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 6,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $products = new WP_Query( $args );
        if ( $products->have_posts() ) :
        ?>
        <div class="empty-cart-products-section">
            <h2 class="empty-cart-products-title">Explore Our Products</h2>
            <div class="empty-cart-products-grid">
                <?php
                while ( $products->have_posts() ) :
                    $products->the_post();
                    $product = wc_get_product( get_the_ID() );
                    if ( ! $product ) continue;
                    ?>
                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="empty-cart-product-card">
                        <div class="empty-cart-product-image">
                            <?php
                            if ( has_post_thumbnail() ) {
                                echo get_the_post_thumbnail( get_the_ID(), 'medium' );
                            } else {
                                echo '📦';
                            }
                            ?>
                        </div>
                        <div class="empty-cart-product-info">
                            <h3 class="empty-cart-product-name"><?php the_title(); ?></h3>
                            <div class="empty-cart-product-price"><?php echo $product->get_price_html(); ?></div>
                        </div>
                    </a>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
        endif;
        ?>

    <?php else : ?>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            
            <div class="custom-cart-items">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        
                        <div class="custom-cart-item">
                            <!-- Column 1: Product Image -->
                            <div class="custom-cart-image">
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                if ( ! $product_permalink ) {
                                    echo $thumbnail;
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                }
                                ?>
                            </div>

                            <!-- Column 2: Product Details (5 rows) -->
                            <div class="custom-cart-details">
                                <!-- Row 1: Title -->
                                <h3 class="custom-product-title">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }
                                    ?>
                                </h3>

                                <!-- Row 2: Quantity -->
                                <div class="custom-detail-row">
                                    <span class="custom-detail-label">Quantity:</span>
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '<span class="custom-item-price">1</span> <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
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
                                    }
                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                    ?>
                                </div>

                                <!-- Row 3: Item Subtotal (Price per item) -->
                                <div class="custom-detail-row">
                                    <span class="custom-detail-label">Price:</span>
                                    <span class="custom-item-price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></span>
                                </div>

                                <!-- Row 4 & 5: Size and Color (if applicable) -->
                                <?php
                                // Get variation data (size, color, etc.)
                                $item_data = wc_get_formatted_cart_item_data( $cart_item, true );
                                if ( $item_data ) {
                                    echo '<div class="custom-variation">' . $item_data . '</div>';
                                }
                                ?>
                            </div>

                            <!-- Column 3: Product Total -->
                            <div class="custom-cart-total">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            </div>

                            <!-- Column 4: Remove Button -->
                            <div class="custom-cart-remove-wrapper">
                                <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" 
                                   class="custom-cart-remove" 
                                   data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" 
                                   aria-label="Remove this item"
                                   data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                   data-product_sku="<?php echo esc_attr( $_product->get_sku() ); ?>">×</a>
                            </div>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if ( ! WC()->cart->is_empty() ) : ?>
            <!-- <button type="button" class="custom-clear-cart-button" style="display:block;margin:0 auto 2rem auto;padding:1rem 2.5rem;background:#ef4444;color:#fff;border:none;border-radius:50px;font-size:0.9rem;font-weight:600;cursor:pointer;">Clear Cart</button> -->
            <?php endif; ?>

            <!-- Hidden update cart button (triggered automatically by JS) -->
            <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display: none;" disabled>
                <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
            </button>
        </form>

        <div class="custom-cart-totals">
            <h2>Cart Totals</h2>
            
            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="custom-totals-row">
                    <span>Coupon: <?php echo esc_html( $code ); ?></span>
                    <span>-<?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                </div>
            <?php endforeach; ?>

            <div class="custom-totals-row">
                <span>Subtotal</span>
                <span><?php wc_cart_totals_subtotal_html(); ?></span>
            </div>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <div class="custom-totals-row">
                    <span><?php echo esc_html( $fee->name ); ?></span>
                    <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                    <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
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

            <div class="custom-totals-row total">
                <span>Total</span>
                <span><?php wc_cart_totals_order_total_html(); ?></span>
            </div>

            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="custom-checkout-button">
                Proceed to Checkout
            </a>
        </div>

    <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
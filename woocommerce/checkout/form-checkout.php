<?php
/**
 * Checkout Form Template
 * Override: woocommerce/checkout/form-checkout.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<style>
.custom-checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.custom-checkout-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: #1f2937;
    text-align: center;
}

.custom-checkout-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
}

.custom-checkout-section {
    background: #f9fafb;
    padding: 2.5rem;
    border-radius: 16px;
}

.custom-checkout-section h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.custom-form-row {
    margin-bottom: 1.5rem;
}

.custom-form-row label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.custom-form-row .required {
    color: #ef4444;
}

.custom-form-row input[type="text"],
.custom-form-row input[type="email"],
.custom-form-row input[type="tel"],
.custom-form-row select,
.custom-form-row textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    transition: border-color 0.3s;
}

.custom-form-row input:focus,
.custom-form-row select:focus,
.custom-form-row textarea:focus {
    outline: none;
    border-color: #667eea;
}

.custom-form-row-half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.custom-order-review {
    background: white;
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    position: sticky;
    top: 2rem;
}

.custom-order-review h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.custom-order-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.custom-order-item-name {
    font-weight: 600;
}

.custom-order-item-quantity {
    color: #6b7280;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}

.custom-order-totals {
    margin-top: 1.5rem;
}

.custom-totals-row {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.custom-totals-row.total {
    font-size: 1.75rem;
    font-weight: 700;
    color: #667eea;
    border-bottom: none;
    padding-top: 1.5rem;
}

.custom-payment-methods {
    margin: 2rem 0;
}

.custom-payment-method {
    padding: 1.25rem;
    margin-bottom: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    cursor: pointer;
    transition: all 0.3s;
}

.custom-payment-method:hover {
    border-color: #667eea;
}

.custom-payment-method input[type="radio"] {
    margin-right: 0.75rem;
}

.custom-payment-method label {
    font-weight: 600;
    font-size: 1.125rem;
    cursor: pointer;
}

.custom-place-order {
    width: 100%;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.25rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.custom-place-order:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.custom-privacy-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 1rem 0;
    text-align: center;
}

@media (max-width: 968px) {
    .custom-checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .custom-order-review {
        position: static;
    }
    
    .custom-form-row-half {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="custom-checkout-container">
    <h1 class="custom-checkout-title">Checkout</h1>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <div class="custom-checkout-grid">
            
            <!-- Left Column: Customer Details -->
            <div class="custom-checkout-left">
                
                <?php if ( $checkout->get_checkout_fields() ) : ?>

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <!-- Billing Details -->
                    <div class="custom-checkout-section">
                        <h3>Billing Details</h3>
                        <?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) : ?>
                            <div class="custom-form-row <?php echo esc_attr( $key ); ?>">
                                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Email Field (if not in billing fields) -->
                        <?php if ( ! isset( $checkout->get_checkout_fields( 'billing' )['billing_email'] ) ) : ?>
                            <div class="custom-form-row billing_email">
                                <?php 
                                woocommerce_form_field( 'billing_email', array(
                                    'type'        => 'email',
                                    'class'       => array( 'form-row-wide' ),
                                    'label'       => __( 'Email Address', 'woocommerce' ),
                                    'placeholder' => __( 'you@example.com', 'woocommerce' ),
                                    'required'    => true,
                                ), $checkout->get_value( 'billing_email' ) ); 
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Shipping Details -->
                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <div class="custom-checkout-section" style="margin-top: 2rem;">
                            <h3>Shipping Details</h3>
                            
                            <?php if ( ! WC()->cart->needs_shipping_address() ) : ?>
                                <p>No shipping needed.</p>
                            <?php else : ?>
                                <?php foreach ( $checkout->get_checkout_fields( 'shipping' ) as $key => $field ) : ?>
                                    <div class="custom-form-row <?php echo esc_attr( $key ); ?>">
                                        <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Additional Information -->
                    <?php if ( $checkout->get_checkout_fields( 'order' ) ) : ?>
                        <div class="custom-checkout-section" style="margin-top: 2rem;">
                            <h3>Additional Information</h3>
                            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                                <div class="custom-form-row <?php echo esc_attr( $key ); ?>">
                                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                <?php endif; ?>
            </div>

            <!-- Right Column: Order Review -->
            <div class="custom-checkout-right">
                <div class="custom-order-review">
                    <h3>Your Order</h3>

                    <!-- Order Items -->
                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                            ?>
                            <div class="custom-order-item">
                                <div>
                                    <span class="custom-order-item-name"><?php echo wp_kses_post( $_product->get_name() ); ?></span>
                                    <span class="custom-order-item-quantity">× <?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                </div>
                                <span><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Order Totals -->
                    <div class="custom-order-totals">
                        <div class="custom-totals-row">
                            <span>Subtotal</span>
                            <span><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <div class="custom-totals-row">
                                <span>Coupon: <?php echo esc_html( $code ); ?></span>
                                <span>-<?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                            <div class="custom-totals-row">
                                <span>Shipping</span>
                                <span><?php wc_cart_totals_shipping_html(); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                            <div class="custom-totals-row">
                                <span><?php echo esc_html( $fee->name ); ?></span>
                                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                                <div class="custom-totals-row">
                                    <span><?php echo esc_html( $tax->label ); ?></span>
                                    <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="custom-totals-row total">
                            <span>Total</span>
                            <span><?php wc_cart_totals_order_total_html(); ?></span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="custom-payment-methods">
                        <?php woocommerce_checkout_payment(); ?>
                    </div>

                    <!-- Privacy Policy -->
                    <?php if ( function_exists( 'wc_get_privacy_policy_text' ) && wc_get_privacy_policy_text() ) : ?>
                        <div class="custom-privacy-text">
                            <?php echo wc_get_privacy_policy_text(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
<?php
/**
 * Checkout Form Template
 * Override: woocommerce/checkout/form-checkout.php
 *
 * REQUIRES: Add the contents of functions-checkout-fragment.php to your theme's functions.php
 * That file registers the AJAX fragment that keeps "Your Order" live without page refresh.
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

.custom-back-to-cart {
    display: inline-block;
    margin-bottom: 2rem;
    color: #7A0E1A;
    text-decoration: underline;
    justify-content: center;
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
    border-color: #7A0E1A;
}

.custom-form-row-half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.custom-order-review {
    background: white;
    padding: 1.5rem;
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
    color: #7A0E1A;
    border-bottom: none;
    padding-top: 1.5rem;
}

.custom-payment-methods {
    margin: 2rem 0;
}



.woocommerce-remove-coupon {
    color: #ef4444 !important;
    text-decoration: none !important;
    font-size: 0.9rem !important;
    margin-left: 0.5rem !important;
}

.woocommerce-Price-amount {
    font-weight: 600 !important;
    color: #1f2937 !important;
    font-size: 1.25rem !important;
}

/* Override WooCommerce payment box styles */
.custom-order-review #payment,
.woocommerce-checkout #payment {
    background: transparent !important;
    border-radius: 0 !important;
    padding: 0 !important;
}

.custom-order-review #payment ul.payment_methods,
.woocommerce-checkout #payment ul.payment_methods {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    background: transparent !important;
}

.custom-order-review #payment ul.payment_methods li,
.woocommerce-checkout #payment ul.payment_methods li {
    padding: 1.25rem !important;
    margin-bottom: 1rem !important;
    background: #f9fafb !important;
    border-radius: 8px !important;
    border: 2px solid #e5e7eb !important;
    transition: all 0.3s !important;
    line-height: 1.5 !important;
}

/* Hide duplicate payment options on small screens */
@media (max-width: 480px) {
    .woocommerce-checkout #payment ul.payment_methods li:not(:first-child) {
        display: none !important;
    }
    
    .woocommerce-checkout #payment ul.payment_methods li:first-child {
        margin-bottom: 0.75rem !important;
    }
}

.custom-order-review #payment ul.payment_methods li:hover,
.woocommerce-checkout #payment ul.payment_methods li:hover {
    border-color: #7A0E1A !important;
}

.custom-order-review #payment ul.payment_methods li label,
.woocommerce-checkout #payment ul.payment_methods li label {
    font-weight: 600 !important;
    font-size: 1.125rem !important;
    cursor: pointer !important;
    color: #1f2937 !important;
    display: inline !important;
    margin: 0 !important;
}

.custom-order-review #payment ul.payment_methods li input[type="radio"],
.woocommerce-checkout #payment ul.payment_methods li input[type="radio"] {
    margin-right: 0.75rem !important;
    vertical-align: middle !important;
}

.custom-order-review #payment .payment_box,
.woocommerce-checkout #payment .payment_box {
    background: #eff6ff !important;
    border-radius: 6px !important;
    padding: 0.5rem !important;
    margin-top: 0.75rem !important;
    font-size: 0.95rem !important;
    color: #374151 !important;
    border: none !important;
}

.custom-order-review #payment .payment_box::before,
.woocommerce-checkout #payment .payment_box::before {
    display: none !important;
}

.custom-order-review #payment div.place-order,
.woocommerce-checkout #payment div.place-order {
    padding: 0 !important;
    margin-top: 1.5rem !important;
    background: transparent !important;
}

.custom-order-review #payment #place_order,
.woocommerce-checkout #payment #place_order {
    width: 100% !important;
    padding: 1.5rem 2rem !important;
background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
 !important;
    color: white !important;
    border: none !important;
    border-radius: 50px !important;
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: all 0.3s !important;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
    text-transform: none !important;
    letter-spacing: normal !important;
    margin-top: 1.5rem !important;
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

/* Extra small devices - iPhone sizes */
@media (max-width: 480px) {
    .custom-checkout-container {
        padding: 2rem 1rem;
    }
    
    .custom-checkout-title {
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
    }
    
    .custom-checkout-section {
        padding: 1.5rem 1rem;
    }
    
    .custom-order-review {
        padding: 1rem;
    }
    
    .custom-order-review h3,
    .custom-checkout-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .custom-form-row input[type="text"],
    .custom-form-row input[type="email"],
    .custom-form-row input[type="tel"],
    .custom-form-row select,
    .custom-form-row textarea {
        padding: 0.75rem 0.875rem;
        font-size: 16px; /* Prevents iOS zoom on input focus */
    }
    
    /* Payment methods - cleaner on mobile */
    .woocommerce-checkout #payment ul.payment_methods li {
        padding: 1rem !important;
        margin-bottom: 0.75rem !important;
    }
    
    .woocommerce-checkout #payment ul.payment_methods li label {
        font-size: 1rem !important;
    }
    
    .woocommerce-checkout #payment .payment_box {
        padding: 1rem !important;
        margin-top: 0.5rem !important;
        font-size: 0.9rem !important;
    }
    
    /* Place order button */
    .woocommerce-checkout #payment #place_order {
        padding: 1.25rem 1rem !important;
        font-size: 1.125rem !important;
    }
    
    .custom-order-item {
        padding: 0.75rem 0;
    }
    
    .custom-totals-row {
        padding: 0.75rem 0;
        font-size: 0.95rem;
    }
    
    .custom-totals-row.total {
        font-size: 1.25rem;
    }
    
    .custom-payment-methods {
        margin: 1.5rem 0;
    }
}

/* Very small phones */
@media (max-width: 375px) {
    .custom-checkout-container {
        padding: 1rem 0.75rem;
    }
    
    .custom-checkout-title {
        font-size: 1.5rem;
    }
    
    .custom-checkout-section,
    .custom-order-review {
        padding: 1rem 0.75rem;
    }
}
</style>

<div class="custom-checkout-container">
    <a href="cart" class="custom-back-to-cart">Back to Cart</a>
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

                    <!--
                        .custom-order-review-inner is registered as a WooCommerce AJAX fragment
                        in functions.php via woocommerce_update_order_review_fragments.
                        WooCommerce replaces this div automatically whenever the checkout
                        recalculates (coupon apply/remove, address entry, shipping change, etc.)
                    -->
                    <div class="custom-order-review-inner">
                        <?php echo custom_checkout_order_review_html(); ?>
                    </div>

                    <!-- Payment Methods — WooCommerce manages its own fragment updates here -->
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


<script>
jQuery(document).ready(function($) {
    // WooCommerce fires update_checkout automatically when address fields change.
    // Our fragment is registered in functions.php, so WC replaces .custom-order-review-inner
    // automatically — no manual AJAX needed.

    // Extra nudge: if a coupon form submits outside normal WC flow, trigger checkout update.
    $(document).on('submit', '.woocommerce-form-coupon', function() {
        setTimeout(function() {
            $(document.body).trigger('update_checkout');
        }, 600);
    });


    // Fix navOverlay if present elsewhere on page
    const navOverlay = document.getElementById('navOverlay');
    if (navOverlay) {
        navOverlay.addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav?.classList.remove('active');
            navOverlay.classList.remove('active');
        });
    }
});
</script>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
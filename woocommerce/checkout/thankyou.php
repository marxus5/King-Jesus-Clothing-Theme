<?php
/**
 * Thankyou page (Order Received)
 * Override: woocommerce/checkout/thankyou.php
 */

defined( 'ABSPATH' ) || exit;

// Remove default WooCommerce order details output
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
remove_action( 'woocommerce_thankyou', 'woocommerce_order_again_button', 20 );

// Hide default order details and customer details sections
add_filter( 'woocommerce_order_details_after_order_table', '__return_false' );
?>

<style>
.custom-thankyou-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.custom-thankyou-header {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #4b4b4b 0%, #0f0f0f 100%);
    border-radius: 16px;
    margin-bottom: 3rem;
    color: white;
}

.custom-thankyou-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    color: white;
    background: #0daf6c;

}

.custom-thankyou-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.custom-thankyou-header p {
    font-size: 1.125rem;
    opacity: 0.95;
}

.custom-order-details {
    background: #f9fafb;
    padding: 2.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
}

@media (max-width: 380px) {
    .custom-order-details {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
}

.custom-order-details h2 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.custom-order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.custom-info-item {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
}

.custom-info-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.custom-info-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.custom-order-items {
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.custom-order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto;
    gap: 1.5rem;
    padding: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    align-items: center;
}

.custom-order-item:last-child {
    border-bottom: none;
}

.custom-item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
}

.custom-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.custom-item-details h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.custom-item-meta {
    font-size: 0.95rem;
    color: #6b7280;
}

.custom-item-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #7A0E1A;
    text-align: right;
}

.custom-order-totals {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-top: 1.5rem;
}

.custom-totals-row {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 1.125rem;
}

.custom-totals-row:last-child {
    border-bottom: none;
}

.custom-totals-row.total {
    font-size: 1.75rem;
    font-weight: 700;
    color: #7A0E1A;
    padding-top: 1.5rem;
}

.custom-addresses {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.custom-address-box {
    background: #f9fafb;
    padding: 2rem;
    border-radius: 12px;
}

.custom-address-box h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1f2937;
}

.custom-address-box address {
    font-style: normal;
    line-height: 1.8;
    color: #4b5563;
}

.custom-actions {
    display: flex;
    gap: 1rem;
    margin-top: 3rem;
    justify-content: center;
}

.custom-action-button {
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-size: 1.125rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-block;
}

.custom-action-primary {
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.custom-action-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.custom-action-secondary {
    background: white;
    color: #1f2937;
    border: 2px solid #e5e7eb;
}

.custom-action-secondary:hover {
    border-color: #7A0E1A;
    color: #7A0E1A;
}

@media (max-width: 768px) {
    .custom-thankyou-header h1 {
        font-size: 2rem;
    }
    
    .custom-addresses {
        grid-template-columns: 1fr;
    }
    
    .custom-order-item {
        grid-template-columns: 60px 1fr;
    }
    
    .custom-item-image {
        width: 60px;
        height: 60px;
    }
    
    .custom-item-total {
        grid-column: 2;
        text-align: left;
        margin-top: 0.5rem;
    }
    
    .custom-actions {
        flex-direction: column;
    }

    .custom-thankyou-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 3rem 0.5rem;
}
}

</style>

<div class="custom-thankyou-container">

    <?php if ( $order ) : ?>

        <!-- Success Header -->
        <div class="custom-thankyou-header">
            <div class="custom-thankyou-icon">✓</div>
            <h1><?php echo apply_filters( 'woocommerce_thankyou_order_received_heading', esc_html__( 'Thank you!', 'woocommerce' ), $order ); ?></h1>
            <p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received.', 'woocommerce' ), $order ); ?></p>
                        <p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'You will receive an email confirmation shortly.', 'woocommerce' ), $order ); ?></p>

        </div>

        <!-- Order Details -->
        <div class="custom-order-details">
            <h2>Order Details</h2>
            
            <div class="custom-order-info">
                

                <div class="custom-info-item">
                    <div class="custom-info-label">Date</div>
                    <div class="custom-info-value"><?php echo wc_format_datetime( $order->get_date_created() ); ?></div>
                </div>

                <div class="custom-info-item">
                    <div class="custom-info-label">Estimated Delivery</div>
                    <div class="custom-info-value"><?php echo esc_html( '10-17 days' ); ?></div>
                </div>

                <div class="custom-info-item">
                    <div class="custom-info-label">Total</div>
                    <div class="custom-info-value"><?php echo $order->get_formatted_order_total(); ?></div>
                </div>

                <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                    <div class="custom-info-item">
                        <div class="custom-info-label">Total Items</div>
                        <div class="custom-info-value"><?php echo $order->get_item_count(); ?></div>
                    </div>
                <?php endif; ?>

                

                <?php if ( $order->get_payment_method_title() ) : ?>
                    <div class="custom-info-item">
                        <div class="custom-info-label">Payment Method</div>
                        <div class="custom-info-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
                    </div>
                <?php endif; ?>

                <div class="custom-info-item">
                    <div class="custom-info-label">Order Number</div>
                    <div class="custom-info-value"><?php echo $order->get_order_number(); ?></div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="custom-order-items">
                <?php
                foreach ( $order->get_items() as $item_id => $item ) :
                    $product = $item->get_product();
                    if ( ! $product ) {
                        continue;
                    }
                    ?>
                    <div class="custom-order-item">
                        <div class="custom-item-image">
                            <?php echo $product->get_image( 'thumbnail' ); ?>
                        </div>

                        <div class="custom-item-details">
                            <h3><?php echo wp_kses_post( $item->get_name() ); ?></h3>
                            <div class="custom-item-meta">
                                Quantity: <?php echo esc_html( $item->get_quantity() ); ?>
                                <?php
                                if ( $item->get_variation_id() ) {
                                    echo '<br>' . wc_get_formatted_variation( $product, true );
                                }
                                ?>
                            </div>
                        </div>

                        <div class="custom-item-total">
                            <?php echo $order->get_formatted_line_subtotal( $item ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Totals -->
            <div class="custom-order-totals">
                <div class="custom-totals-row">
                    <span>Subtotal</span>
                    <span><?php echo $order->get_subtotal_to_display(); ?></span>
                </div>

                <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                    <?php if ( $key !== 'payment_method' ) : ?>
                        <div class="custom-totals-row <?php echo ( 'order_total' === $key ) ? 'total' : ''; ?>">
                            <span><?php echo esc_html( $total['label'] ); ?></span>
                            <span><?php echo wp_kses_post( $total['value'] ); ?></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Addresses -->
        <div class="custom-addresses">
            <div class="custom-address-box">
                <h3>Billing Address</h3>
                <address>
                    <?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
                    <?php if ( $order->get_billing_phone() ) : ?>
                        <br>Phone: <?php echo esc_html( $order->get_billing_phone() ); ?>
                    <?php endif; ?>
                    <?php if ( $order->get_billing_email() ) : ?>
                        <br>Email: <?php echo esc_html( $order->get_billing_email() ); ?>
                    <?php endif; ?>
                </address>
            </div>

            <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>
                <div class="custom-address-box">
                    <h3>Shipping Address</h3>
                    <address>
                        <?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
                    </address>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="custom-actions">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-action-button custom-action-primary">
                Continue Shopping
            </a>
            <?php if ( is_user_logged_in() ) : ?>
                <!-- <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="custom-action-button custom-action-secondary">
                    View Orders
                </a> -->
            <?php endif; ?>
        </div>

    <?php else : ?>

        <div class="custom-thankyou-header">
            <div class="custom-thankyou-icon">!</div>
            <h1><?php esc_html_e( 'Order not found', 'woocommerce' ); ?></h1>
            <p><?php esc_html_e( 'This order cannot be found.', 'woocommerce' ); ?></p>
        </div>

        <div class="custom-actions">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-action-button custom-action-primary">
                Continue Shopping
            </a>
        </div>

    <?php endif; ?>

</div>
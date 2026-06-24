<?php
/**
 * Empty cart page
 * Override: woocommerce/cart/cart-empty.php
 */

defined( 'ABSPATH' ) || exit;
?>

<style>
.custom-empty-cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 4rem 2rem;
    text-align: center;
}

.custom-empty-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    margin: 0 auto 2rem;
    opacity: 0.9;
}

.custom-empty-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1f2937;
}

.custom-empty-message {
    font-size: 1.25rem;
    color: #6b7280;
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.custom-shop-button {
    display: inline-block;
    padding: 1.25rem 3rem;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1.25rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    text-decoration: none;
}

.custom-shop-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.custom-suggestions-section {
    margin-top: 4rem;
}

.custom-suggestions-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: #1f2937;
}

.custom-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.custom-product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration: none;
    display: block;
    color: inherit;
}

.custom-product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.custom-product-image {
    width: 100%;
    height: 280px;
    background: linear-gradient(135deg #f34040 0%, #830b15 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    overflow: hidden;
}

.custom-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.custom-product-content {
    padding: 1.5rem;
}

.custom-product-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1f2937;
}

.custom-product-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #7A0E1A;
    margin-bottom: 1rem;
}

.custom-view-product {
    display: block;
    width: 100%;
    padding: 0.75rem;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.custom-view-product:hover {
    background: #7A0E1A;
    color: white;
}

@media (max-width: 768px) {
    .custom-empty-title {
        font-size: 2rem;
    }
    
    .custom-empty-message {
        font-size: 1.125rem;
    }
    
    .custom-products-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>

<div class="custom-empty-cart-container">

    
    <h1 class="custom-empty-title"><?php esc_html_e( 'Your cart is empty', 'woocommerce' ); ?></h1>
    
    <p class="custom-empty-message">
        <?php esc_html_e( "Looks like you haven't added anything to your cart yet. Browse our collection and find something you love!", 'woocommerce' ); ?>
    </p>
    
    <!-- Shop Button -->
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="custom-shop-button">
        <?php esc_html_e( 'Start Shopping', 'woocommerce' ); ?>
    </a>
    
    <!-- Product Suggestions -->
    <div class="custom-suggestions-section">
        <h2 class="custom-suggestions-title">You Might Like These</h2>
        
        <div class="custom-products-grid">
            <?php
            // Best-sellers (falls back to newest) via the shared cart helper.
            $suggested = function_exists( 'kjc_get_cart_recommendations' ) ? kjc_get_cart_recommendations( 3 ) : array();

            if ( ! empty( $suggested ) ) :
                foreach ( $suggested as $suggested_id ) :
                    $suggested_product = wc_get_product( $suggested_id );
                    if ( ! $suggested_product ) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $suggested_id ) ); ?>" class="custom-product-card">
                        <div class="custom-product-image">
                            <?php echo $suggested_product->get_image( 'woocommerce_thumbnail' ); ?>
                        </div>
                        <div class="custom-product-content">
                            <h3 class="custom-product-title"><?php echo esc_html( $suggested_product->get_name() ); ?></h3>
                            <p class="custom-product-price"><?php echo wp_kses_post( $suggested_product->get_price_html() ); ?></p>
                            <span class="custom-view-product">View Product</span>
                        </div>
                    </a>
                    <?php
                endforeach;
            else :
                // Fallback message if no products exist
                ?>
                <p style="grid-column: 1/-1; text-align: center; color: #6b7280;">
                    <?php esc_html_e( 'No products available at the moment. Check back soon!', 'woocommerce' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    
</div>


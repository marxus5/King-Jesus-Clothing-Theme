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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    color: #667eea;
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
    background: #667eea;
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
            // Get featured or recent products
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 3,
                'orderby' => 'rand', // Random products
                'post_status' => 'publish',
            );
            
            $products = new WP_Query( $args );
            
            if ( $products->have_posts() ) :
                while ( $products->have_posts() ) : $products->the_post();
                    global $product;
                    ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="custom-product-card">
                        <div class="custom-product-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail('full'); ?>
                            <?php else : ?>
                                👕
                            <?php endif; ?>
                        </div>
                        <div class="custom-product-content">
                            <h3 class="custom-product-title"><?php the_title(); ?></h3>
                            <p class="custom-product-price"><?php echo $product->get_price_html(); ?></p>
                            <span class="custom-view-product">View Product</span>
                        </div>
                    </a>
                    <?php
                endwhile;
                wp_reset_postdata();
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


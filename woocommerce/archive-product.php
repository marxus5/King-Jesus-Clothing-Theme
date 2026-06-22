<?php
/**
 * Shop / Product Archive Template
 * Override: woocommerce/archive-product.php
 *
 * Place at: yourtheme/woocommerce/archive-product.php
 *
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'woocommerce_before_main_content' );
?>

<style>
/* ── Shop Page ─────────────────────────────────────────────── */
.custom-shop-container {
    max-width: 100%;
    margin: 0;
    padding: 3rem 0.5rem;
}

.custom-shop-header {
    text-align: center;
    margin-bottom: 3rem;
}

.custom-shop-title {
    font-size: 2.75rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.75rem;
}

.custom-shop-description {
    font-size: 1.125rem;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
}

/* Toolbar: result count + sorting */
.custom-shop-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f3f4f6;
    flex-wrap: wrap;
    gap: 1rem;
}

.custom-shop-toolbar .woocommerce-result-count {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
}

.custom-shop-toolbar .woocommerce-ordering select {
    padding: 0.6rem 2.5rem 0.6rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #374151;
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
}

.custom-shop-toolbar .woocommerce-ordering select:focus {
    outline: none;
    border-color: #7A0E1A;
}

/* Product Grid — target columns-4 specifically since that's what WC outputs */
.custom-shop-container ul.products.columns-4,
.custom-shop-container ul.products.columns-3,
.custom-shop-container ul.products.columns-2,
.custom-shop-container ul.products {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 0.35rem !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    float: none !important;
    width: 100% !important;
    max-width: 100% !important;
}

/* Reset li — must beat ul.products.columns-4 li.product { width: 25% } */
.custom-shop-container ul.products.columns-4 li.product,
.custom-shop-container ul.products.columns-3 li.product,
.custom-shop-container ul.products.columns-2 li.product,
.custom-shop-container ul.products li.product {
    width: auto !important;
    max-width: none !important;
    min-width: 0 !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    clear: none !important;
}

/* Pagination */
.custom-shop-pagination {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.custom-shop-pagination .woocommerce-pagination ul {
    display: flex;
    list-style: none;
    gap: 0.5rem;
    padding: 0;
    margin: 0;
}

.custom-shop-pagination .woocommerce-pagination ul li a,
.custom-shop-pagination .woocommerce-pagination ul li span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 0.6rem;
    border-radius: 8px;
    border: none;
    background: transparent;
    font-weight: 600;
    color: #7A0E1A;
    text-decoration: none;
    transition: all 0.2s;
}

.custom-shop-pagination .woocommerce-pagination ul li a:hover,
.custom-shop-pagination .woocommerce-pagination ul li span.current {
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
    border-color: transparent;
    color: white;
}

/* Empty state */
.custom-no-products {
    text-align: center;
    padding: 5rem 2rem;
    color: #6b7280;
}

.custom-no-products p {
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 900px) {
    .custom-shop-container ul.products.columns-4,
    .custom-shop-container ul.products.columns-3,
    .custom-shop-container ul.products {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 640px) {
    .custom-shop-container ul.products.columns-4,
    .custom-shop-container ul.products.columns-3,
    .custom-shop-container ul.products {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    .custom-shop-title { font-size: 2rem; }
}
</style>

<div class="custom-shop-container">

    <!-- Header -->
    <div class="custom-shop-header">
        <?php
        if ( is_shop() ) {
            echo '<h1 class="custom-shop-title">' . get_the_title( wc_get_page_id( 'shop' ) ) . '</h1>';
            $shop_description = get_post_field( 'post_content', wc_get_page_id( 'shop' ) );
            if ( $shop_description ) {
                echo '<p class="custom-shop-description">' . wp_kses_post( $shop_description ) . '</p>';
            }
        } elseif ( is_product_category() ) {
            $cat = get_queried_object();
            echo '<h1 class="custom-shop-title">' . esc_html( $cat->name ) . '</h1>';
            if ( $cat->description ) {
                echo '<p class="custom-shop-description">' . wp_kses_post( $cat->description ) . '</p>';
            }
        } else {
            the_archive_title( '<h1 class="custom-shop-title">', '</h1>' );
        }
        ?>
    </div>

    <?php if ( woocommerce_product_loop() ) : ?>

        <!-- Toolbar -->
        <div class="custom-shop-toolbar">
            <?php woocommerce_result_count(); ?>
            <?php woocommerce_catalog_ordering(); ?>
        </div>

        <!-- Product Grid -->
        <?php
        add_filter( 'loop_shop_columns', function() { return 3; }, 99 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count',   20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
        do_action( 'woocommerce_before_shop_loop' );
        ?>

        <ul class="products columns-3">
        <?php
        while ( have_posts() ) {
            the_post();
            do_action( 'woocommerce_shop_loop' );
            wc_get_template_part( 'content', 'product' );
        }
        ?>
        </ul>

        <!-- Pagination -->
        <div class="custom-shop-pagination">
            <?php woocommerce_pagination(); ?>
        </div>

    <?php else : ?>

        <div class="custom-no-products">
            <?php do_action( 'woocommerce_no_products_found' ); ?>
        </div>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var navOverlay = document.getElementById('navOverlay');
    if (navOverlay) {
        navOverlay.addEventListener('click', function() {
            var nav = document.querySelector('nav');
            if (nav) nav.classList.remove('active');
            navOverlay.classList.remove('active');
        });
    }
});
</script>

<?php
do_action( 'woocommerce_after_main_content' );
get_footer();
?>
<?php
/**
 * Template Name: Returns & Refunds Policy
 */

get_header(); ?>

<style>
    /* Policy Page Styling */
    .policy-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 2rem;
        background: #ffffff;
    }

    .policy-header {
        background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
        padding: 6rem 2rem 4rem;
        text-align: center;
        color: white;
        margin-bottom: 3rem;
    }

    .policy-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }

    .policy-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
    }

    .policy-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #1a1a1a;
        border-bottom: 2px solid #7A0E1A;
        padding-bottom: 0.5rem;
    }

    .policy-content h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #333;
    }

    .policy-content p {
        margin-bottom: 1.25rem;
    }

    .policy-content ul,
    .policy-content ol {
        margin: 1.25rem 0 1.25rem 2rem;
    }

    .policy-content li {
        margin-bottom: 0.75rem;
    }

    .policy-last-updated {
        background: #f5f7fb;
        padding: 1.25rem;
        border-radius: 10px;
        margin-top: 3rem;
        font-size: 0.95rem;
        color: #666;
    }

    .contact-section {
        background: #f9fafc;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 2rem;
    }

    .contact-section h3 {
        color: #7A0E1A;
    }
</style>

<div class="policy-header">
    <h1>Returns & Refunds Policy</h1>
    <p>Our commitment to your satisfaction</p>
</div>

<div class="policy-container">
    <div class="policy-content">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        } else {
            echo '<p>Please add your returns and refunds policy content to this page.</p>';
        }
        ?>
    </div>

    <div class="contact-section">
        <h3>Questions?</h3>
        <p>If you have any questions about our returns and refunds policy, please don't hesitate to <a href="<?php echo home_url('/contact'); ?>">contact us</a>.</p>
    </div>

    <div class="policy-last-updated">
        <strong>Last Updated:</strong> <?php echo date('F j, Y'); ?>
    </div>
</div>

<?php get_footer(); ?>

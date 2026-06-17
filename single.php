<?php
/**
 * Template Name: Single Post
 * Description: Individual blog post page
 */

get_header(); ?>

<style>
/* Post Hero */
.post-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 4rem 2rem 3rem;
    position: relative;
    overflow: hidden;
}

.post-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
    background-size: 50px 50px;
    opacity: 0.3;
}

.post-hero-content {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.post-back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    margin-bottom: 2rem;
    font-weight: 500;
    transition: all 0.3s;
}

.post-back-link:hover {
    color: white;
    gap: 0.75rem;
}

.post-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.post-category-badge {
    padding: 0.5rem 1rem;
    background: rgba(102, 126, 234, 0.2);
    color: #a5b4fc;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.post-category-badge:hover {
    background: #7A0E1A;
    color: white;
}

.post-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: white;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    letter-spacing: -1px;
}

.post-meta {
    display: flex;
    gap: 2rem;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
}

.post-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Post Content */
.post-content-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 4rem 2rem;
}

.post-featured-image {
    width: 100%;
    height: 500px;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 3rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8rem;
}

.post-featured-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-content {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
}

.post-content h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 2.5rem 0 1.5rem;
    line-height: 1.3;
}

.post-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 2rem 0 1rem;
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content ul,
.post-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.post-content li {
    margin-bottom: 0.75rem;
}

.post-content a {
    color: #7A0E1A;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: border-color 0.3s;
}

.post-content a:hover {
    border-bottom-color: #7A0E1A;
}

.post-content blockquote {
    border-left: 4px solid #7A0E1A;
    padding-left: 2rem;
    margin: 2rem 0;
    font-style: italic;
    font-size: 1.25rem;
    color: #4b5563;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 2rem 0;
}

/* Post Footer */
.post-footer {
    max-width: 900px;
    margin: 3rem auto 0;
    padding: 2rem;
    border-top: 2px solid #e5e7eb;
}

.post-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 2rem;
}

.post-tags-label {
    font-weight: 600;
    color: #1f2937;
    margin-right: 0.5rem;
}

.post-tag {
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s;
}

.post-tag:hover {
    background: #7A0E1A;
    color: white;
}

.post-author-box {
    background: #f9fafb;
    padding: 2rem;
    border-radius: 16px;
    display: flex;
    gap: 2rem;
    align-items: center;
}

.post-author-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f34040 0%, #830b15 100%);
;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.post-author-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.post-author-info h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.post-author-bio {
    color: #6b7280;
    line-height: 1.6;
}

/* Navigation */
.post-navigation {
    max-width: 900px;
    margin: 3rem auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.post-nav-link {
    padding: 2rem;
    background: white;
    border-radius: 16px;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.post-nav-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.post-nav-label {
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 0.5rem;
    display: block;
}

.post-nav-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
}

.post-nav-prev {
    text-align: left;
}

.post-nav-next {
    text-align: right;
}

/* Related Posts */
.related-posts {
    background: #f9fafb;
    padding: 4rem 2rem;
}

.related-posts-container {
    max-width: 1200px;
    margin: 0 auto;
}

.related-posts h3 {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 3rem;
    color: #1f2937;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.related-post-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s;
    text-decoration: none;
    display: block;
}

.related-post-card:hover {
    transform: translateY(-8px);
}

.related-post-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.related-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-post-content {
    padding: 1.5rem;
}

.related-post-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.related-post-date {
    font-size: 0.875rem;
    color: #9ca3af;
}

/* Responsive */
@media (max-width: 768px) {
    .post-title {
        font-size: 2rem;
    }
    
    .post-featured-image {
        height: 300px;
        font-size: 4rem;
    }
    
    .post-content {
        font-size: 1rem;
    }
    
    .post-navigation {
        grid-template-columns: 1fr;
    }
    
    .post-author-box {
        flex-direction: column;
        text-align: center;
    }
    
    .related-posts-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php while (have_posts()) : the_post(); ?>

<!-- Post Hero -->
<section class="post-hero">
    <div class="post-hero-content">
        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="post-back-link">
            ← Back to Blog
        </a>
        
        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
        ?>
            <div class="post-categories">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="post-category-badge">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h1 class="post-title"><?php the_title(); ?></h1>
        
        <div class="post-meta">
            <span class="post-meta-item">
                📅 <?php echo get_the_date('F j, Y'); ?>
            </span>
            <span class="post-meta-item">
                ✍️ <?php the_author(); ?>
            </span>
            <span class="post-meta-item">
                ⏱️ <?php echo get_the_modified_time('F j, Y'); ?>
            </span>
        </div>
    </div>
</section>

<!-- Post Content -->
<article class="post-content-wrapper">
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>
    
    <div class="post-content">
        <?php the_content(); ?>
    </div>
    
    <!-- Tags & Author -->
    <div class="post-footer">
        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
            <div class="post-tags">
                <span class="post-tags-label">Tags:</span>
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="post-tag">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="post-author-box">
            <div class="post-author-avatar">
                <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
            </div>
            <div class="post-author-info">
                <h4>Written by <?php the_author(); ?></h4>
                <p class="post-author-bio">
                    <?php echo get_the_author_meta('description') ?: 'Contributing writer sharing insights on style, trends, and fashion.'; ?>
                </p>
            </div>
        </div>
    </div>
</article>

<!-- Post Navigation -->
<nav class="post-navigation">
    <?php
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ($prev_post) :
    ?>
        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="post-nav-link post-nav-prev">
            <span class="post-nav-label">← Previous Post</span>
            <span class="post-nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
        </a>
    <?php else : ?>
        <div></div>
    <?php endif; ?>
    
    <?php if ($next_post) : ?>
        <a href="<?php echo get_permalink($next_post->ID); ?>" class="post-nav-link post-nav-next">
            <span class="post-nav-label">Next Post →</span>
            <span class="post-nav-title"><?php echo esc_html($next_post->post_title); ?></span>
        </a>
    <?php endif; ?>
</nav>

<?php endwhile; ?>

<!-- Related Posts -->
<?php
$categories = get_the_category();
if ($categories) {
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $related_args = array(
        'category__in' => $category_ids,
        'post__not_in' => array(get_the_ID()),
        'posts_per_page' => 3,
        'orderby' => 'rand'
    );
    
    $related_posts = new WP_Query($related_args);
    
    if ($related_posts->have_posts()) :
    ?>
        <section class="related-posts">
            <div class="related-posts-container">
                <h3>You Might Also Like</h3>
                <div class="related-posts-grid">
                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="related-post-card">
                            <div class="related-post-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    📰
                                <?php endif; ?>
                            </div>
                            <div class="related-post-content">
                                <h4 class="related-post-title"><?php the_title(); ?></h4>
                                <p class="related-post-date"><?php echo get_the_date('F j, Y'); ?></p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    <?php
    endif;
    wp_reset_postdata();
}
?>

<?php get_footer(); ?>
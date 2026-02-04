<?php
/**
 * Template Name: Blog Archive
 * Description: Blog listing page
 */

get_header(); ?>

<style>
/* Blog Archive Hero */
.blog-hero {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    padding: 6rem 2rem 4rem;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.blog-hero::before {
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

.blog-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
}

.blog-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    letter-spacing: -1px;
}

.blog-hero p {
    font-size: 1.25rem;
    opacity: 0.9;
}

/* Blog Container */
.blog-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 4rem 2rem;
}

/* Blog Grid */
.blog-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 3rem;
    margin-bottom: 4rem;
}

.blog-post-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
}

.blog-post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.blog-post-image {
    width: 100%;
    height: 280px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    overflow: hidden;
    position: relative;
}

.blog-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-post-content {
    padding: 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.blog-post-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

.blog-post-date,
.blog-post-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.blog-post-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1f2937;
    line-height: 1.3;
}

.blog-post-title a {
    color: #1f2937;
    text-decoration: none;
    transition: color 0.3s;
}

.blog-post-title a:hover {
    color: #667eea;
}

.blog-post-excerpt {
    font-size: 1rem;
    color: #6b7280;
    line-height: 1.7;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}

.blog-post-readmore {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: gap 0.3s;
}

.blog-post-readmore:hover {
    gap: 1rem;
}

.blog-post-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.blog-category {
    padding: 0.25rem 0.75rem;
    background: #f3f4f6;
    color: #4b5563;
    border-radius: 50px;
    font-size: 0.813rem;
    text-decoration: none;
    transition: all 0.3s;
}

.blog-category:hover {
    background: #667eea;
    color: white;
}

/* Pagination */
.blog-pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 3rem;
}

.page-numbers {
    padding: 0.75rem 1.25rem;
    background: white;
    color: #1f2937;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.page-numbers:hover,
.page-numbers.current {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.page-numbers.dots {
    border: none;
    background: transparent;
}

/* No Posts */
.no-posts {
    text-align: center;
    padding: 4rem 2rem;
}

.no-posts h2 {
    font-size: 2rem;
    color: #1f2937;
    margin-bottom: 1rem;
}

.no-posts p {
    font-size: 1.125rem;
    color: #6b7280;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-hero h1 {
        font-size: 2.5rem;
    }
    
    .blog-posts-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .blog-post-title {
        font-size: 1.5rem;
    }
}
</style>

<!-- Blog Hero -->
<section class="blog-hero">
    <div class="blog-hero-content">
        <h1>Our Blog</h1>
        <p>Style tips, trends, and stories from our community</p>
    </div>
</section>

<!-- Blog Posts -->
<div class="blog-container">
    <?php if (have_posts()) : ?>
        <div class="blog-posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article class="blog-post-card">
                    <div class="blog-post-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else : ?>
                            📰
                        <?php endif; ?>
                    </div>
                    
                    <div class="blog-post-content">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) :
                        ?>
                            <div class="blog-post-categories">
                                <?php foreach ($categories as $category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="blog-category">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="blog-post-meta">
                            <span class="blog-post-date">
                                📅 <?php echo get_the_date('F j, Y'); ?>
                            </span>
                            <span class="blog-post-author">
                                ✍️ <?php the_author(); ?>
                            </span>
                        </div>
                        
                        <h2 class="blog-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <div class="blog-post-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="blog-post-readmore">
                            Read Full Story →
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <div class="blog-pagination">
            <?php
            echo paginate_links(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
                'type' => 'plain',
            ));
            ?>
        </div>
        
    <?php else : ?>
        <div class="no-posts">
            <h2>No posts yet</h2>
            <p>Check back soon for fresh content!</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
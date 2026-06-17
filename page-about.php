<?php
/**
 * Template Name: About Us
 */

get_header(); ?>

<style>
    /* About Page Styling */
    .about-hero {
        background: linear-gradient(135deg, #4b4b4b 0%, #0f0f0f 100%);
        padding: 6rem 2rem 4rem;
        text-align: center;
        color: white;
    }

    .about-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }

    .about-hero p {
        font-size: 1.25rem;
        opacity: 0.95;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    /* Mission Statement Section */
    .mission-section {
        background: #f9fafc;
        padding: 4rem 2rem;
        border-radius: 16px;
        margin-bottom: 4rem;
    }

    .mission-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .mission-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #7A0E1A;
        margin-bottom: 1rem;
    }

    .mission-header .tagline {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .mission-content {
        max-width: 900px;
        margin: 0 auto;
    }

    .mission-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 1.5rem;
    }

    .mission-content strong {
        color: #7A0E1A;
    }

    /* Image Gallery Section */
    .gallery-section {
        margin-top: 4rem;
        margin-bottom: 4rem;
    }

    .gallery-section h2 {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        color: #1a1a1a;
        margin-bottom: 3rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .gallery-item {
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: translateY(-8px);
    }

    .gallery-item img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        display: block;
    }

    /* Values Section */
    .values-section {
        background: linear-gradient(135deg, #4b4b4b 0%, #0f0f0f 100%);
        padding: 4rem 2rem;
        border-radius: 16px;
        color: white;
        margin-top: 4rem;
    }

    .values-section h2 {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .value-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .value-card h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .value-card p {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2rem;
        }

        .mission-header h2 {
            font-size: 1.5rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .values-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="about-hero">
    <h1>About King Jesus Clothing</h1>
    <p>Spreading the Gospel Through Fashion</p>
</div>

<div class="about-container">
    <!-- Mission Statement -->
    <div class="mission-section">
        <div class="mission-header">
            <h2>Our Mission</h2>
        </div>
        <div class="mission-content">
            <h3>"What did Jesus say?"</h3>
            <p>This is the <strong>heartbeat of King Jesus Clothing</strong>. We exist to focus on, teach, and spread the living words and teachings of Christ and His Kingdom—not only what He spoke in Scripture, but what He is speaking today to our generation and the body of Christ.</p>
            
            <p><strong>Our ultimate goal:</strong></p>
            <ul style="margin-left: 1.5rem; line-height: 2;">
                <li>To proclaim the gospel of the Kingdom</li>
                <li>Create fresh opportunities for evangelism and discipleship</li>
                <li>Draw people into deeper intimacy with God</li>
                <li>Literally clothe them in Christ through every garment we create</li>
            </ul>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="gallery-section">
        <!-- <h2>Our Journey</h2> -->
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/lyn-modle-pic.jpg" alt="Gallery Image 1" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22280%22 height=%22280%22%3E%3Crect fill=%22%23ddd%22 width=%22280%22 height=%22280%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2216%7D fill=%7Bcolor: #999; text-anchor: middle; dy: .3em; } %3EImage 1%3C/text%3E%3C/svg%3E'">
            </div>
            <div class="gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Friend-of-God-Model-Pic.jpg" alt="Gallery Image 2" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22280%22 height=%22280%22%3E%3Crect fill=%22%23ddd%22 width=%22280%22 height=%22280%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2216%7D fill=%7Bcolor: #999; text-anchor: middle; dy: .3em; } %3EImage 1%3C/text%3E%3C/svg%3E'">
            </div>
            <div class="gallery-item">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Black-Hoodies-Modle-Pic.jpg" alt="Gallery Image 3" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22280%22 height=%22280%22%3E%3Crect fill=%22%23ddd%22 width=%22280%22 height=%22280%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-size=%2220%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EImage 3%3C/text%3E%3C/svg%3E'">
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="values-section">
        <h2>Our Core Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <h3>Gospel Centered</h3>
                <p>Everything we do is rooted in the gospel of Jesus Christ and His Kingdom message.</p>
            </div>
            <div class="value-card">
                <h3>Quality Focused</h3>
                <p>We create premium garments that reflect the value and worth we have in Christ.</p>
            </div>
            <div class="value-card">
                <h3>Community Driven</h3>
                <p>We build a community of believers who are passionate about spreading Christ's message.</p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

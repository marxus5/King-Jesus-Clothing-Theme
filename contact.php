<?php
/**
 * Template Name: Contact Page
 */

get_header(); ?>

<style>
    /* Contact Hero */
    .contact-hero {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        padding: 6rem 2rem 4rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
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

    .contact-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }

    .contact-hero h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: -1px;
    }

    .contact-hero p {
        font-size: 1.25rem;
        opacity: 0.9;
    }

    /* Contact Container */
    .contact-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 5rem 2rem;
    }

    /* Contact Grid */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-bottom: 4rem;
    }

    /* Contact Info Section */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 3rem;
    }

    .contact-info-item {
        background: #ffffff;
        padding: 2.5rem;
        border-radius: 16px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .contact-info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    .contact-info-item h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .contact-info-item a {
        color: #667eea;
        text-decoration: none;
        font-size: 1.125rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .contact-info-item a:hover {
        color: #764ba2;
    }

    /* Social Links Section */
    .social-section {
        background: white;
        padding: 3rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .social-section h3 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2rem;
        text-align: center;
    }

    .social-links {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem 2rem;
        background: #f9fafb;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .social-link:hover {
        transform: translateX(10px);
        border-color: #667eea;
        background: white;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .social-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .social-icon.facebook {
        background: #1877f2;
        color: white;
    }

    .social-icon.instagram {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        color: white;
    }

    .social-icon.tiktok {
        background: #000000;
        color: white;
    }

    .social-content {
        flex-grow: 1;
    }

    .social-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        display: block;
        margin-bottom: 0.25rem;
    }

    .social-handle {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .social-arrow {
        color: #667eea;
        font-size: 1.5rem;
        transition: transform 0.3s;
    }

    .social-link:hover .social-arrow {
        transform: translateX(5px);
    }

    /* Message Section */
    .message-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 4rem 3rem;
        border-radius: 16px;
        text-align: center;
        color: white;
        margin-top: 4rem;
    }

    .message-section h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .message-section p {
        font-size: 1.125rem;
        opacity: 0.95;
        line-height: 1.7;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .contact-hero h1 {
            font-size: 2.5rem;
        }
        
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        
        .social-link {
            padding: 1.25rem 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .contact-container {
            padding: 3rem 1.5rem;
        }
        
        .social-section {
            padding: 2rem 1.5rem;
        }
        
        .message-section {
            padding: 3rem 2rem;
        }

        .social-handle {
            font-size: 0.7rem;
            color: #6b7280;
        }
    }

    @media (max-width: 370px) {
        .social-handle {
            font-size: 0.6rem;
            color: #6b7280;
        }

         .social-arrow {
            display: none;
        }

        .social-name {
            margin-bottom: 0;
        }
        
    }
</style>

<!-- Contact Hero -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>Get In Touch</h1>
        <p>We'd love to hear from you. Connect with us!</p>
    </div>
</section>

<!-- Contact Content -->
<div class="contact-container">
    <!-- <div class="contact-grid"> -->
        <!-- Contact Info -->
        <div class="contact-info">
            <div class="contact-info-item">
                <div class="contact-icon">✉️</div>
                <h3>Email Us</h3>
                <a href="mailto:contact@kingjesus-clothing.org">contact@kingjesus-clothing.org</a>
            </div>
        </div>
        
        <!-- Social Links -->
        <div class="social-section">
            <h3>Follow Us</h3>
            <div class="social-links">
                <!-- Facebook -->
                <a href="https://facebook.com/kingjesusclothingbrand" target="_blank" rel="noopener noreferrer" class="social-link">
                    <div class="social-icon facebook fab fa-facebook-f"></div>
                    <div class="social-content">
                        <span class="social-name">Facebook</span>
                        <span class="social-handle">@kingjesusclothingbrand</span>
                    </div>
                    <span class="social-arrow">→</span>
                </a>
                
                <!-- Instagram -->
                <a href="https://instagram.com/kingjesusclothingbrand" target="_blank" rel="noopener noreferrer" class="social-link">
                    <div class="social-icon instagram fab fa-instagram"></div>
                    <div class="social-content">
                        <span class="social-name">Instagram</span>
                        <span class="social-handle">@kingjesusclothingbrand</span>
                    </div>
                    <span class="social-arrow">→</span>
                </a>
                
                <!-- TikTok -->
                <a href="https://tiktok.com/@kingjesusclothingbrand" target="_blank" rel="noopener noreferrer" class="social-link">
                    <div class="social-icon tiktok fab fa-tiktok"></div>
                    <div class="social-content">
                        <span class="social-name">TikTok</span>
                        <span class="social-handle">@kingjesusclothingbrand</span>
                    </div>
                    <span class="social-arrow">→</span>
                </a>
            </div>
        <!-- </div> -->
    </div>
    
    <!-- Message Section -->
    <!-- <div class="message-section">
        <h3>Join Our Community</h3>
        <p>Stay connected with King Jesus Clothing for the latest drops, exclusive deals, and inspiration. We're more than just apparel — we're a movement.</p>
    </div> -->
</div>

<?php get_footer(); ?>
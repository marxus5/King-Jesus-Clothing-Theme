<?php
/**
 * Template Name: Homepage v2
 * Description: New modern homepage design with hero, products, mission, reviews
 */

get_header();

// Enqueue homepage v2 assets
wp_enqueue_style('homepage-v2', get_template_directory_uri() . '/css/homepage-v2.css', array(), '1.0');
wp_enqueue_script('homepage-v2', get_template_directory_uri() . '/js/homepage-v2.js', array(), '1.0', true);
?>

<style>
  :root {
    --black: #1D1D1D;
    --red: #CE202F;
    --red-deep: #7A0E1A;
    --brown: #8B5E3C;
    --brown-light: #A0714F;
    --cream: #FAF3EB;
    --gold: #dfb543;
    --gold-light: #50322F;
    --white: #FFFFFF;
    --off-white: #FAF3EB;
    --text: #1D1D1D;
    --text-muted: #5A4A3A;
    --font: 'Segoe UI', system-ui, -apple-system, sans-serif;
    --banner-h: 38px;
    --nav-h: 68px;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; }

  body {
    background: var(--white);
    color: var(--text);
    font-family: var(--font);
    overflow-x: hidden;
  }

  /* ─── TOP BANNER — always fixed at very top ─── */
  .top-banner {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: var(--banner-h);
    background: var(--red);
    text-align: center;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.08em;
    color: #fff;
    z-index: 300;
  }

  /* ─── NAVBAR — sticky just below banner ─── */
  .navbar {
    position: fixed;
    top: var(--banner-h);
    left: 0; right: 0;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 5vw;
    height: var(--nav-h);
    transition: background 0.3s ease, box-shadow 0.3s ease;
    background: transparent;
  }
  /* When page is at top AND on homepage, nav is transparent over hero */
  .navbar.at-top { background: transparent; box-shadow: none; }
  /* Once scrolled, or on non-hero pages, nav is solid white */
  .navbar.scrolled {
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
  }

  /* Text colors in transparent state (over dark hero) */
  .navbar.at-top .nav-left a,
  .navbar.at-top .nav-brand,
  .navbar.at-top .cart-icon { color: #fff; }
  .navbar.at-top .hamburger span { background: #fff; }

  /* Text colors in solid state */
  .navbar.scrolled .nav-left a,
  .navbar.scrolled .nav-brand,
  .navbar.scrolled .cart-icon { color: var(--text); }
  .navbar.scrolled .hamburger span { background: var(--text); }

  .nav-left { display: flex; gap: 28px; align-items: center; }
  .nav-left a {
    text-decoration: none; font-size: 13px; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase; transition: color 0.2s;
  }
  .nav-left a:hover { color: var(--red) !important; }

  .nav-center {
    position: absolute; left: 50%; transform: translateX(-50%);
    display: flex; align-items: center; gap: 10px;
  }
  .nav-logo {
    width: 36px; height: 36px; background: var(--red); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 13px; color: #fff; flex-shrink: 0;
  }
  .nav-brand {
    font-weight: 700; font-size: 15px; letter-spacing: 0.08em;
    text-decoration: none; white-space: nowrap; text-transform: uppercase;
    transition: color 0.3s;
  }

  .nav-right { display: flex; align-items: center; gap: 20px; margin-left: auto; }
  .cart-wrap { position: relative; cursor: pointer; }
  .cart-icon { width: 22px; height: 22px; display: block; transition: color 0.3s; }
  .cart-badge {
    position: absolute; top: -7px; right: -8px;
    background: var(--red); color: #fff; font-size: 10px; font-weight: 700;
    width: 17px; height: 17px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
  }
  .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; }
  .hamburger span { display: block; width: 22px; height: 2px; transition: 0.3s; }

  @media (max-width: 768px) {
    .nav-left { display: none; }
    .hamburger { display: flex; }
    .nav-brand { font-size: 12px; }
  }

  .mobile-menu {
    display: none; position: fixed; inset: 0;
    background: rgba(255,255,255,0.98); z-index: 199;
    flex-direction: column; align-items: center; justify-content: center; gap: 32px;
  }
  .mobile-menu.open { display: flex; }
  .mobile-menu a {
    color: var(--text); text-decoration: none; font-size: 22px;
    font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; transition: color 0.2s;
  }
  .mobile-menu a:hover { color: var(--red); }
  .mobile-close { position: absolute; top: 24px; right: 24px; font-size: 28px; cursor: pointer; color: var(--text); }

  /* ─── PAGE OFFSET for fixed banner+nav ─── */
  .page-body { padding-top: 0; /* Banner+nav are transparent over hero, so no padding needed */ }

  /* ─── HERO ─── */
  /* Hero sits at top of .page-body so the transparent nav overlays it */
/* HERO */
.hero {
    position: relative;
    width: 100vw;
    height: 68vh;           /* Good balance */
    min-height: 460px;
    max-height: 720px;
    overflow: hidden;
    margin-top: calc(-1 * var(--nav-h));
    /* background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/NewHero.jpg'); */
    background-size: cover;
        background-position: center 30%;   /* Adjust this number to focus on important part */

  }

.hero-bg {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(135deg, 
            rgba(0,0,0,0.08) 0%, 
            rgba(206,32,47,0.22) 45%, 
            rgba(206,32,47,0.68) 100%),
        linear-gradient(135deg, #3a0008 0%, #7a0012 40%, #b0001a 70%, #CE202F 100%);    
    background-size: cover;
    background-repeat: no-repeat;
    z-index: -1;
}

/* Mobile Optimization */
@media (max-width: 768px) {
    .hero {
        height: 20vh;
        min-height: 300px;
        margin-top: 1rem; /* Add some spacing on mobile */
    }

    nav
    
    .hero-bg {
        background-position: center 50%;   /* Different focus for mobile */
    }
}
  .hero-cross {
    position: absolute; inset: 0; opacity: 0.05;
    background-image:
      repeating-linear-gradient(0deg, transparent, transparent 58px, rgba(255,255,255,0.5) 58px, rgba(255,255,255,0.5) 62px),
      repeating-linear-gradient(90deg, transparent, transparent 58px, rgba(255,255,255,0.5) 58px, rgba(255,255,255,0.5) 62px);
  }
  .hero-content {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 0 6vw 44px;
    animation: heroFadeUp 1s ease 0.2s both;
  }
  .hero-eyebrow {
    font-size: 11px; letter-spacing: 0.3em; text-transform: uppercase;
    color: var(--gold-light); margin-bottom: 12px; font-weight: 600;
  }
  .hero-title {
    font-size: clamp(32px, 6vw, 72px); font-weight: 800;
    line-height: 1.05; margin-bottom: 28px; color: #fff; letter-spacing: -0.01em;
  }
  .hero-cta {
    display: inline-flex; align-items: center; gap: 10px;
    background: #fff; color: var(--red);
    text-decoration: none; padding: 16px 36px;
    font-weight: 700; font-size: 13px;
    letter-spacing: 0.1em; text-transform: uppercase;
    border: none; cursor: pointer; border-radius: 0;
    border-bottom: 3px solid var(--gold);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .hero-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.18); }
  @keyframes heroFadeUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

  /* ─── SECTIONS ─── */
  .section { padding: 80px 5vw; background: var(--white); }
  .section.offset { background: var(--off-white); }

  .section-label {
    font-size: 11px; letter-spacing: 0.3em; text-transform: uppercase;
    color: var(--brown); margin-bottom: 10px; font-weight: 700;
  }
  .section-title {
    font-size: clamp(22px, 4vw, 40px); font-weight: 800;
    margin-bottom: 40px; line-height: 1.15; color: var(--text); letter-spacing: -0.01em;
  }

  /* ─── PRODUCTS ─── */
  .products-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
  @media (min-width: 640px) { .products-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; } }
  @media (min-width: 1024px) { .products-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; } }

  .product-card {
    cursor: pointer; text-decoration: none; color: var(--text); display: block;
    opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease;
  }
  .product-card.visible { opacity: 1; transform: translateY(0); }
  .product-img { width: 100%; aspect-ratio: 3/4; background: var(--off-white); position: relative; overflow: hidden; margin-bottom: 12px; }
  .product-img-inner {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    font-size: 11px; letter-spacing: 0.15em; color: #bbb; text-transform: uppercase;
    background: linear-gradient(135deg, #f0f0f0, #e8e8e8); transition: transform 0.4s ease;
  }
  .product-img::after { content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 3px; background: var(--gold); transition: width 0.35s ease; }
  .product-card:hover .product-img::after { width: 100%; }
  .product-card:hover .product-img-inner { transform: scale(1.03); }
  .product-name { font-size: 13px; font-weight: 600; margin-bottom: 5px; }
  .product-price { font-size: 14px; font-weight: 700; color: var(--red); }

  /* ─── MISSION / VALUES ─── */
  .mission-section { padding: 80px 5vw; background: var(--off-white); }
  .mission-header { text-align: center; margin-bottom: 56px; }
  .mission-quote {
    font-size: clamp(15px, 2.2vw, 20px); font-style: italic; line-height: 1.75;
    max-width: 640px; margin: 0 auto 40px; color: var(--text-muted);
  }
  .values-grid { display: grid; grid-template-columns: 1fr; gap: 24px; max-width: 900px; margin: 0 auto 56px; }
  @media (min-width: 640px) { .values-grid { grid-template-columns: repeat(3, 1fr); } }

  .value-card {
    text-align: center; padding: 32px 20px; border: 1px solid #e8e8e8; background: var(--white);
    position: relative; opacity: 0; transform: translateY(24px); transition: opacity 0.5s, transform 0.5s;
  }
  .value-card.visible { opacity: 1; transform: translateY(0); }
  .value-card::before { content: ''; position: absolute; top: 0; left: 0; width: 0; height: 3px; background: var(--gold); transition: width 0.4s ease; }
  .value-card:hover::before { width: 100%; }
  .value-icon { font-size: 32px; margin-bottom: 14px; display: block; }
  .value-title { font-size: 15px; font-weight: 700; margin-bottom: 10px; }
  .value-desc { font-size: 13px; color: var(--text-muted); line-height: 1.7; }

  .perks-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; max-width: 800px; margin: 0 auto; }
  @media (min-width: 640px) { .perks-row { grid-template-columns: repeat(4, 1fr); } }
  .perk-item { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px 10px; text-align: center; opacity: 0; transform: translateY(16px); transition: opacity 0.4s, transform 0.4s; }
  .perk-item.visible { opacity: 1; transform: translateY(0); }
  .perk-icon { font-size: 24px; }
  .perk-label { font-size: 12px; font-weight: 600; letter-spacing: 0.06em; color: var(--text-muted); }

  /* ─── REVIEWS ─── */
  .reviews-section { padding: 80px 0; background: var(--white); overflow: hidden; }
  .reviews-header { padding: 0 5vw; margin-bottom: 40px; }
  .overall-rating { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
  .stars-big { font-size: 24px; letter-spacing: 2px; color: var(--gold); }
  .rating-num { font-size: 36px; font-weight: 800; }
  .rating-count { font-size: 13px; color: var(--text-muted); }
  .reviews-subtitle { font-size: 13px; color: var(--text-muted); max-width: 420px; line-height: 1.7; }

  .carousel-track-wrap { overflow: hidden; }
  .carousel-track { display: flex; gap: 20px; padding: 10px 5vw 20px; animation: scrollLeft 40s linear infinite; width: max-content; }
  .carousel-track:hover { animation-play-state: paused; }
  @keyframes scrollLeft { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

  .review-card { background: var(--off-white); border: 1px solid #ebebeb; padding: 24px; width: 280px; flex-shrink: 0; position: relative; }
  .review-card::before { content: '"'; position: absolute; top: 12px; right: 18px; font-size: 60px; color: var(--red); opacity: 0.1; line-height: 1; }
  .review-top { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 14px; }
  .review-avatar { width: 42px; height: 42px; border-radius: 50%; background: var(--red); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: #fff; flex-shrink: 0; }
  .review-name { font-weight: 700; font-size: 14px; margin-bottom: 3px; }
  .review-verified { font-size: 11px; color: var(--red); font-weight: 600; }
  .review-stars { font-size: 13px; color: var(--gold); margin-bottom: 12px; }
  .review-text { font-size: 13px; color: var(--text-muted); line-height: 1.7; }
  .review-img { width: 60px; height: 60px; background: #e8e8e8; margin-top: 14px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #aaa; }

  /* ─── ABOUT ─── */
  .about-section { padding: 80px 5vw; background: var(--off-white); display: grid; grid-template-columns: 1fr; gap: 48px; align-items: center; }
  @media (min-width: 768px) { .about-section { grid-template-columns: 1fr 1fr; } }
  .about-img { aspect-ratio: 4/5; background: linear-gradient(135deg, #f5e8d8, #e8d0b8); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; opacity: 0; transform: translateX(-30px); transition: opacity 0.7s, transform 0.7s; }
  .about-img.visible { opacity: 1; transform: translateX(0); }
  .about-img-placeholder { font-size: 12px; letter-spacing: 0.2em; color: #bbb; text-align: center; text-transform: uppercase; }
  .about-img::before { content: ''; position: absolute; bottom: 0; left: 0; width: 4px; height: 100%; background: var(--brown); }
  .about-text { opacity: 0; transform: translateX(30px); transition: opacity 0.7s 0.2s, transform 0.7s 0.2s; }
  .about-text.visible { opacity: 1; transform: translateX(0); }
  .about-text p { font-size: 15px; line-height: 1.8; color: var(--text-muted); margin-bottom: 28px; }
  .btn-outline { display: inline-block; border: 2px solid var(--red); color: var(--red); padding: 14px 32px; font-size: 12px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; text-decoration: none; transition: background 0.25s, color 0.25s; cursor: pointer; }
  .btn-outline:hover { background: var(--red); color: #fff; }

  /* ─── FAQ ─── */
  .faq-outer { padding: 80px 5vw; background: var(--white); }
  .faq-inner { max-width: 760px; margin: 0 auto; }
  .faq-item { border-bottom: 1px solid #e8e8e8; opacity: 0; transform: translateY(12px); transition: opacity 0.4s, transform 0.4s; }
  .faq-item.visible { opacity: 1; transform: translateY(0); }
  .faq-q { display: flex; justify-content: space-between; align-items: center; padding: 22px 0; cursor: pointer; font-size: 15px; font-weight: 600; gap: 20px; user-select: none; }
  .faq-arrow { width: 18px; height: 18px; border-right: 2px solid var(--brown); border-bottom: 2px solid var(--brown); transform: rotate(45deg) translateY(-4px); transition: transform 0.3s; flex-shrink: 0; }
  .faq-item.open .faq-arrow { transform: rotate(-135deg) translateY(-4px); }
  .faq-a { max-height: 0; overflow: hidden; font-size: 14px; color: var(--text-muted); line-height: 1.8; transition: max-height 0.4s ease, padding 0.3s; }
  .faq-item.open .faq-a { max-height: 200px; padding-bottom: 20px; }

  /* ─── SCRIPTURE ─── */
  .scripture-section { padding: 100px 5vw; text-align: center; background: linear-gradient(135deg, #CE202F 0%, #e85060 50%, #f08090 100%); position: relative; overflow: hidden; }
  .scripture-section::before { content: '✝'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: clamp(180px, 30vw, 300px); color: rgba(255,255,255,0.1); line-height: 1; pointer-events: none; }
  .scripture-text { font-size: clamp(20px, 4vw, 42px); font-weight: 300; font-style: italic; line-height: 1.5; max-width: 800px; margin: 0 auto 20px; color: #fff; position: relative; opacity: 0; transform: translateY(24px); transition: opacity 0.8s, transform 0.8s; }
  .scripture-text.visible { opacity: 1; transform: translateY(0); }
  .scripture-ref { font-size: 14px; letter-spacing: 0.2em; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; }

  /* ─── FOOTER ─── */
  .footer { background: var(--black); padding: 60px 5vw 32px; border-top: 2px solid var(--brown); }
  .footer-grid { display: grid; grid-template-columns: 1fr; gap: 40px; margin-bottom: 48px; }
  @media (min-width: 640px) { .footer-grid { grid-template-columns: 2fr 1fr 1fr; } }
  .footer-brand { font-size: 18px; font-weight: 800; margin-bottom: 12px; color: #fff; text-transform: uppercase; letter-spacing: 0.05em; }
  .footer-tagline { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.7; max-width: 260px; }
  .footer-col h4 { font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gold); margin-bottom: 16px; font-weight: 700; }
  .footer-col a { display: block; color: rgba(255,255,255,0.55); text-decoration: none; font-size: 13px; margin-bottom: 10px; transition: color 0.2s; }
  .footer-col a:hover { color: #fff; }
  .footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding-top: 24px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
  .footer-copy { font-size: 12px; color: rgba(255,255,255,0.3); }

  /* ─── MODAL ─── */
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.35s; }
  .modal-overlay.open { opacity: 1; pointer-events: all; }
  .modal { background: #fff; border: 1px solid #eee; max-width: 480px; width: 100%; padding: 48px 36px 40px; position: relative; transform: translateY(30px) scale(0.96); transition: transform 0.35s; text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,0.12); }
  .modal-overlay.open .modal { transform: translateY(0) scale(1); }
  .modal-close { position: absolute; top: 16px; right: 20px; background: none; border: none; color: #aaa; font-size: 22px; cursor: pointer; transition: color 0.2s; line-height: 1; }
  .modal-close:hover { color: var(--text); }
  .modal-badge { display: inline-block; background: var(--red); color: #fff; font-size: 11px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; padding: 6px 14px; margin-bottom: 18px; border-bottom: 2px solid var(--gold); }
  .modal h2 { font-size: clamp(22px, 5vw, 30px); font-weight: 800; margin-bottom: 10px; line-height: 1.2; color: var(--text); }
  .modal p { font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 28px; }
  .modal-form { display: flex; flex-direction: column; gap: 12px; }
  .modal-input { background: var(--off-white); border: 1px solid #e0e0e0; color: var(--text); padding: 14px 16px; font-family: var(--font); font-size: 14px; outline: none; transition: border-color 0.2s; width: 100%; }
  .modal-input:focus { border-color: var(--red); }
  .modal-input::placeholder { color: #aaa; }
  .modal-submit { background: var(--red); color: #fff; border: none; padding: 16px; font-family: var(--font); font-size: 13px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; cursor: pointer; transition: opacity 0.2s, transform 0.2s; margin-top: 4px; }
  .modal-submit:hover { opacity: 0.88; transform: translateY(-1px); }
  .modal-skip { font-size: 12px; color: #aaa; margin-top: 14px; cursor: pointer; transition: color 0.2s; }
  .modal-skip:hover { color: var(--text); text-decoration: underline; }

  /* ─── STICKY 15% TRIANGLE — bottom corner, no glow ─── */
  .sticky-promo { position: fixed; bottom: 0; left: 0; z-index: 500; cursor: pointer; opacity: 0; pointer-events: none; transition: opacity 0.3s, transform 0.3s; transform: translateX(-6px); }
  .sticky-promo.show { opacity: 1; pointer-events: all; transform: translateX(0); }
  .sticky-triangle { width: 0; height: 0; border-top: 56px solid transparent; border-bottom: 56px solid transparent; border-left: 84px solid var(--red); position: relative; }
  .sticky-triangle-text { position: absolute; top: 50%; left: -78px; transform: translateY(-50%); width: 66px; text-align: center; color: #fff; font-size: 11px; font-weight: 800; letter-spacing: 0.04em; line-height: 1.3; pointer-events: none; }

  /* ─── SCROLL REVEALS ─── */
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .reveal.visible { opacity: 1; transform: translateY(0); }

  .product-card:nth-child(1){transition-delay:.05s} .product-card:nth-child(2){transition-delay:.10s} .product-card:nth-child(3){transition-delay:.15s} .product-card:nth-child(4){transition-delay:.20s} .product-card:nth-child(5){transition-delay:.25s} .product-card:nth-child(6){transition-delay:.30s} .product-card:nth-child(7){transition-delay:.05s} .product-card:nth-child(8){transition-delay:.10s} .product-card:nth-child(9){transition-delay:.15s} .product-card:nth-child(10){transition-delay:.20s} .product-card:nth-child(11){transition-delay:.25s} .product-card:nth-child(12){transition-delay:.30s}
  .value-card:nth-child(2){transition-delay:.12s} .value-card:nth-child(3){transition-delay:.24s}
  .perk-item:nth-child(2){transition-delay:.08s} .perk-item:nth-child(3){transition-delay:.16s} .perk-item:nth-child(4){transition-delay:.24s}
  .faq-item:nth-child(2){transition-delay:.06s} .faq-item:nth-child(3){transition-delay:.12s} .faq-item:nth-child(4){transition-delay:.18s} .faq-item:nth-child(5){transition-delay:.24s}
</style>
</head>
<body>

<!-- PAGE BODY (offset for fixed banner+nav) -->
<div class="page-body">

  <!-- HERO (nav overlays because of negative margin-top) -->
  <div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-cross"></div>
    <div class="hero-content">
      <div class="hero-eyebrow">New Collection — Spring 2025</div>
      <h1 class="hero-title">Wear Your<br>Faith Boldly</h1>
      <a href="#products" class="hero-cta">
        Shop Our Best Sellers
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>

  <!-- FEATURED PRODUCTS -->
  <section class="section" id="products">
    <div class="reveal">
      <div class="section-label">Best Sellers</div>
      <h2 class="section-title">Featured Products</h2>
    </div>
    <div class="products-grid">
      <?php
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $products = new WP_Query( $args );

      if ( $products->have_posts() ) {
        while ( $products->have_posts() ) {
          $products->the_post();
          global $product;
          ?>
          <a href="<?php the_permalink(); ?>" class="product-card">
            <div class="product-img">
              <div class="product-img-inner">
                <?php
                if ( has_post_thumbnail() ) {
                  the_post_thumbnail( 'large' );
                } else {
                  echo 'No Image';
                }
                ?>
              </div>
            </div>
            <div class="product-name"><?php the_title(); ?></div>
            <div class="product-price"><?php echo $product->get_price_html(); ?></div>
          </a>
          <?php
        }
        wp_reset_postdata();
      }
      ?>    </div>
  </section>

  <!-- MISSION / VALUES -->
  <section class="mission-section">
    <div class="mission-header reveal">
      <div class="section-label">Our Mission</div>
      <h2 class="section-title" style="text-align:center">Clothed in Purpose</h2>
      <p class="mission-quote">"We create clothing that doesn't just look good — it starts conversations, declares faith, and supports those spreading the Gospel across the world."</p>
    </div>
    <div class="values-grid">
      <div class="value-card"><span class="value-icon">✝</span><div class="value-title">Gospel First</div><div class="value-desc">Every design starts with Scripture. We want every piece to point back to Jesus Christ.</div></div>
      <div class="value-card"><span class="value-icon">🌍</span><div class="value-title">Global Mission</div><div class="value-desc">A portion of every purchase directly funds missionaries and church plants worldwide.</div></div>
      <div class="value-card"><span class="value-icon">🔥</span><div class="value-title">Bold Witness</div><div class="value-desc">Our clothing is a conversation starter. Wear it, share it, spark faith in others.</div></div>
    </div>
    <div class="perks-row">
      <div class="perk-item"><span class="perk-icon">📦</span><div class="perk-label">Free Shipping $80+</div></div>
      <div class="perk-item"><span class="perk-icon">↩</span><div class="perk-label">Easy Returns</div></div>
      <div class="perk-item"><span class="perk-icon">🔒</span><div class="perk-label">Secure Checkout</div></div>
      <div class="perk-item"><span class="perk-icon">🙏</span><div class="perk-label">Supporting Missionaries</div></div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="reviews-section">
    <div class="reviews-header reveal">
      <div class="overall-rating">
        <span class="stars-big">★★★★★</span>
        <span class="rating-num">4.9</span>
        <span class="rating-count">from 1,284 reviews</span>
      </div>
      <h2 class="section-title">What Our Community Says</h2>
      <p class="reviews-subtitle">Real believers wearing their faith and sharing their stories. Every review is from a verified purchase.</p>
    </div>
    <div class="carousel-track-wrap">
      <div class="carousel-track">
        <div class="review-card"><div class="review-top"><div class="review-avatar">JM</div><div><div class="review-name">James Miller</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"I wore the King Jesus tee to college and had 3 people ask about my faith. This clothing opens doors for the Gospel."</div><div class="review-img">📸 Photo</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">SR</div><div><div class="review-name">Sarah Rodriguez</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Incredible quality. Soft, durable, and the designs are genuinely beautiful. I bought 4 pieces and plan to gift them to my youth group."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">DC</div><div><div class="review-name">David Chen</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Shipped to Australia super fast. The Lion of Judah hoodie is my favorite piece. Bold design without being cheesy."</div><div class="review-img">📸 Photo</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">AT</div><div><div class="review-name">Ashley Turner</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"I love that they support missionaries. It feels meaningful to buy from a kingdom-minded brand. Customer for life."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">MP</div><div><div class="review-name">Marcus Price</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Ordered three hoodies for our small group leaders. Everyone loved them. Sizing is true and print quality is excellent."</div><div class="review-img">📸 Photo</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">LW</div><div><div class="review-name">Lydia Wallace</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Faith Over Fear crewneck arrived fast and looks even better in person. Rich color and incredible detail."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">RP</div><div><div class="review-name">Robert Park</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"First Christian brand that feels modern. My non-believing friends are curious about the designs — great conversations."</div><div class="review-img">📸 Photo</div></div>
        <!-- Duplicate for seamless loop -->
        <div class="review-card"><div class="review-top"><div class="review-avatar">JM</div><div><div class="review-name">James Miller</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"I wore the King Jesus tee to college and had 3 people ask about my faith. This clothing opens doors for the Gospel."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">SR</div><div><div class="review-name">Sarah Rodriguez</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Incredible quality. Soft, durable, and the designs are genuinely beautiful. I bought 4 pieces and plan to gift them to my youth group."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">DC</div><div><div class="review-name">David Chen</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"Shipped to Australia super fast. The Lion of Judah hoodie is my favorite piece. Bold design without being cheesy."</div></div>
        <div class="review-card"><div class="review-top"><div class="review-avatar">AT</div><div><div class="review-name">Ashley Turner</div><div class="review-verified">✓ Verified Buyer</div></div></div><div class="review-stars">★★★★★</div><div class="review-text">"I love that they support missionaries. It feels meaningful to buy from a kingdom-minded brand. Customer for life."</div></div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="about-section">
    <div class="about-img" id="aboutImg"><div class="about-img-placeholder">YOUR BRAND<br>IMAGE HERE</div></div>
    <div class="about-text" id="aboutText">
      <div class="section-label">Our Story</div>
      <h2 class="section-title">Born from a<br>Calling, Not a Trend</h2>
      <p>King Jesus Clothing started with a simple belief: that fashion could be used for the Kingdom. We're a family-owned brand rooted in faith, dedicated to creating clothing that reflects the heart of the Gospel — bold, beautiful, and built to last.</p>
      <p>Every stitch supports our mission of funding missionaries planting churches in unreached corners of the world. When you wear King Jesus, you're not just wearing clothes. You're wearing a declaration.</p>
      <a href="#" class="btn-outline">Our Full Story →</a>
    </div>
  </section>

  <!-- FAQ -->
  <div class="faq-outer">
    <div class="faq-inner">
      <div class="reveal" style="margin-bottom:40px">
        <div class="section-label">FAQ</div>
        <h2 class="section-title">Common Questions</h2>
      </div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How long does shipping take? <div class="faq-arrow"></div></div><div class="faq-a">Domestic US orders ship within 2–4 business days. International orders typically arrive in 7–14 business days. Free international shipping on all orders over $80.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">What is your return policy? <div class="faq-arrow"></div></div><div class="faq-a">We offer easy 30-day returns on all unworn, unwashed items in original condition. Simply contact us and we'll send a return label. Exchanges are always free.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How do your sizes run? <div class="faq-arrow"></div></div><div class="faq-a">Our garments are true to size with a slightly relaxed fit. We recommend checking the size chart on each product page. If you're between sizes, size up for a more comfortable fit.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">How do you support missionaries? <div class="faq-arrow"></div></div><div class="faq-a">A portion of every purchase is donated to vetted missionary organizations working in unreached people groups. We partner with field missionaries personally and report transparently on how funds are used each quarter.</div></div>
      <div class="faq-item" onclick="toggleFaq(this)"><div class="faq-q">Do you offer bulk or church orders? <div class="faq-arrow"></div></div><div class="faq-a">Yes! We offer special pricing for churches, youth groups, and ministries ordering 10+ items. Reach out through our Contact page and we'll take care of you.</div></div>
    </div>
  </div>

  <!-- SCRIPTURE -->
  <section class="scripture-section">
    <div class="scripture-text" id="scripture">"I have been crucified with Christ. It is no longer I who live, but Christ who lives in me."</div>
    <div class="scripture-ref">Galatians 2:20</div>
  </section>
</div><!-- end .page-body -->



<!-- <script>
  // ─── CONFIG: replace with your Google Apps Script Web App URL ───────────────
  // Steps:
  // 1. Open Google Sheets → Extensions → Apps Script
  // 2. Paste the doPost function from the comment below and deploy as Web App
  // 3. Replace the URL below with your deployment URL
  //
  // Apps Script code to paste:
  // function doPost(e) {
  //   var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  //   var data = JSON.parse(e.postData.contents);
  //   sheet.appendRow([new Date(), data.name, data.email, data.source]);
  //   return ContentService.createTextOutput(JSON.stringify({result:'success'}))
  //     .setMimeType(ContentService.MimeType.JSON);
  // }
const GOOGLE_SHEET_URL = 'https://script.google.com/macros/s/AKfycbyr2xZ_M-2QYKkUF3H8aeMZgdJNT0nfd0iqYqIe_yU7ehErEPuVuwIsKE8EhlRRuGoX/exec';

async function sendToGoogleSheet(name, email, source = 'website') {
  try {
    const response = await fetch(GOOGLE_SHEET_URL, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json' 
      },
      body: JSON.stringify({ 
        name, 
        email, 
        source,
        timestamp: new Date().toISOString() 
      })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    console.log('✅ Successfully added to sheet:', result);
    return result;

  } catch (err) {
    console.error('❌ Sheet submit error:', err);
    // Optional: fallback behavior
    alert("Thanks for subscribing! (There was a small connection issue)");
  }
} -->

  // ─── NAVBAR ─────────────────────────────────────────────────────────────────
  const navbar = document.getElementById('navbar');
  // Hero height threshold — once scrolled past nav height we solidify
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.classList.remove('at-top');
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
      navbar.classList.add('at-top');
    }
  });

  // ─── MOBILE MENU ────────────────────────────────────────────────────────────
  function toggleMenu() { document.getElementById('mobileMenu').classList.toggle('open'); }

  // ─── SCROLL REVEAL ──────────────────────────────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal, .product-card, .value-card, .perk-item, .faq-item, #aboutImg, #aboutText, #scripture');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => obs.observe(el));

  // ─── FAQ ────────────────────────────────────────────────────────────────────
  function toggleFaq(item) { item.classList.toggle('open'); }


</script>

<?php get_footer(); ?>
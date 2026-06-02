// ─── SCROLL REVEAL ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  const revealEls = document.querySelectorAll('.reveal, .product-card, .value-card, .perk-item, .faq-item, #aboutImg, #aboutText, #scripture');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => obs.observe(el));
});

// ─── FAQ ────────────────────────────────────────────────────────────
function toggleFaq(item) { item.classList.toggle('open'); }

// Note: Modal functions are now defined in header.php for global availability
// and to prevent conflicts with the centralized modal system

/**
 * Quantity stepper for the single-product add-to-cart.
 *
 * Wraps WooCommerce's .quantity field in a .kjc-qty pill and injects − / +
 * buttons so it matches the cart page's stepper. Styles live in
 * single-product.php (.custom-product-add-to-cart .kjc-qty*).
 */
(function () {
    function buildStepper(qtyEl) {
        // Skip if already wrapped/processed.
        if (!qtyEl || qtyEl.dataset.kjcStepper === '1' || qtyEl.closest('.kjc-qty')) {
            return;
        }
        var input = qtyEl.querySelector('input.qty');
        if (!input) {
            return;
        }
        qtyEl.dataset.kjcStepper = '1';

        var wrap = document.createElement('span');
        wrap.className = 'kjc-qty';
        qtyEl.parentNode.insertBefore(wrap, qtyEl);

        var dec = document.createElement('button');
        dec.type = 'button';
        dec.className = 'kjc-qty__btn';
        dec.setAttribute('aria-label', 'Decrease quantity');
        dec.innerHTML = '&minus;';

        var inc = document.createElement('button');
        inc.type = 'button';
        inc.className = 'kjc-qty__btn';
        inc.setAttribute('aria-label', 'Increase quantity');
        inc.textContent = '+';

        wrap.appendChild(dec);
        wrap.appendChild(qtyEl); // move .quantity into the pill
        wrap.appendChild(inc);

        function step(dir) {
            var stepv = parseFloat(input.getAttribute('step')) || 1;
            var min = parseFloat(input.getAttribute('min'));
            if (isNaN(min)) { min = 1; }
            var maxAttr = input.getAttribute('max');
            var max = (maxAttr === '' || maxAttr === null) ? Infinity : parseFloat(maxAttr);
            var val = parseFloat(input.value) || min;
            val += dir * stepv;
            if (val < min) { val = min; }
            if (val > max) { val = max; }
            input.value = val;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }

        dec.addEventListener('click', function () { step(-1); });
        inc.addEventListener('click', function () { step(1); });
    }

    function init() {
        document.querySelectorAll('.custom-product-add-to-cart .quantity').forEach(buildStepper);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Variable products reveal the quantity once a variation is chosen — re-run
    // (idempotent thanks to the guards above).
    document.addEventListener('change', function (e) {
        if (e.target && e.target.closest && e.target.closest('.variations')) {
            setTimeout(init, 60);
        }
    });
})();

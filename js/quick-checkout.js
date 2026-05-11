/**
 * Quick Checkout & Banner Manager
 * Handles Stripe Express Checkout and free shipping banner
 */

// ====================================
// 1. FREE SHIPPING BANNER MANAGER
// ====================================

// Banner is always visible (no animations)



/**
 * Initialize Stripe Payment Request Button on product pages
 * Requires: stripe.js, stripe public key via window object or data attribute
 */

document.addEventListener('DOMContentLoaded', function() {
    const expressCheckoutContainer = document.getElementById('stripe-express-checkout-container');
    
    // Only run on product pages
    if (!expressCheckoutContainer) {
        return;
    }
    
    // Wait for Stripe to load (should be enqueued in functions.php)
    if (typeof window.stripe_config === 'undefined') {
        console.log('Stripe config not available, skipping Express Checkout');
        return;
    }
    
    initializeExpressCheckout();
});

/**
 * Initialize Express Checkout button
 */
function initializeExpressCheckout() {
    const stripe = window.stripe_config.stripe;
    const clientSecret = window.stripe_config.clientSecret;
    const currency = window.stripe_config.currency || 'usd';
    
    if (!stripe) {
        console.error('Stripe not initialized');
        return;
    }
    
    // Get product data from the page
    const productData = getProductData();
    
    if (!productData) {
        console.log('Could not extract product data');
        return;
    }
    
    // Create Payment Request
    const paymentRequest = stripe.paymentRequest({
        country: 'US',
        currency: currency.toLowerCase(),
        total: {
            label: productData.name,
            amount: productData.amount,
        },
        requestPayerEmail: true,
        requestPayerName: true,
    });
    
    // Create and mount Payment Request Button Element
    const elements = stripe.elements();
    const prButton = elements.create('paymentRequestButton', {
        paymentRequest: paymentRequest,
    });
    
    // Check the availability of the Payment Request API first
    paymentRequest.canMakePayment().then(function(result) {
        if (result) {\n            // Button will be shown\n            prButton.mount('#payment-request-button');\n        } else {\n            // Payment Request API is not available\n            // Hide the container\n            document.getElementById('stripe-express-checkout-container').style.display = 'none';\n        }\n    });\n    \n    // Handle payment method selected\n    paymentRequest.on('paymentmethod', async function(ev) {\n        // Confirm the PaymentIntent with the payment method from the Payment Request API\n        const confirmResult = await stripe.confirmCardPayment(clientSecret, {\n            payment_method: ev.paymentMethod.id,\n        }, {\n            handleActions: false,\n        });\n        \n        if (confirmResult.error) {\n            // Show error to your customer\n            ev.complete('fail');\n            console.error('Payment failed:', confirmResult.error.message);\n            alert('Payment failed: ' + confirmResult.error.message);\n        } else {\n            ev.complete('success');\n            \n            // The payment succeeded\n            // Redirect to order confirmation page\n            if (confirmResult.paymentIntent.status === 'succeeded') {\n                // Get order key from the payment intent\n                window.location.href = confirmResult.paymentIntent.metadata.checkout_redirect || '/checkout/';\n            }\n        }\n    });\n}\n\n/**\n * Extract product data from the page\n * Returns {name, amount, quantity, product_id, variation_id}\n */\nfunction getProductData() {\n    try {\n        // Get product name\n        const titleEl = document.querySelector('.custom-product-detail-title');\n        if (!titleEl) return null;\n        const name = titleEl.textContent.trim();\n        \n        // Get product price (in cents for Stripe)\n        const priceEl = document.querySelector('.custom-product-detail-price .price');\n        let amount = 0;\n        \n        if (priceEl) {\n            // Extract price from text (e.g., \"$99.99\")\n            const priceText = priceEl.textContent.replace(/[^\\d.]/g, '');\n            amount = Math.round(parseFloat(priceText) * 100);\n        }\n        \n        if (amount <= 0) {\n            console.error('Invalid product price');\n            return null;\n        }\n        \n        // Get quantity from quantity input\n        const qtyInput = document.querySelector('.custom-product-add-to-cart .quantity input.qty');\n        const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;\n        \n        // Adjust amount for quantity\n        const totalAmount = amount * quantity;\n        \n        // Get product ID from the form or page data\n        const productId = window.wc_product_id || document.querySelector('input[name=\"product_id\"]')?.value;\n        \n        return {\n            name: name,\n            amount: totalAmount,\n            quantity: quantity,\n            product_id: productId,\n        };\n    } catch (error) {\n        console.error('Error extracting product data:', error);\n        return null;\n    }\n}\n\n/**\n * Update payment amount when quantity changes\n */\nfunction updatePaymentAmount() {\n    const qtyInput = document.querySelector('.custom-product-add-to-cart .quantity input.qty');\n    if (qtyInput) {\n        qtyInput.addEventListener('change', function() {\n            // Re-initialize Express Checkout with updated amount\n            const expressCheckoutContainer = document.getElementById('stripe-express-checkout-container');\n            if (expressCheckoutContainer) {\n                // Clear and reinitialize\n                const paymentRequestBtn = document.getElementById('payment-request-button');\n                if (paymentRequestBtn) {\n                    paymentRequestBtn.innerHTML = '';\n                    initializeExpressCheckout();\n                }\n            }\n        });\n    }\n}\n\n// Initialize on page load\nif (document.readyState === 'loading') {\n    document.addEventListener('DOMContentLoaded', updatePaymentAmount);\n} else {\n    updatePaymentAmount();\n}\n
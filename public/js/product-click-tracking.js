// Product Click Tracking
(function() {
    'use strict';
    
    // Prevent duplicate tracking
    if (window.ProductClickTrackingInitialized) {
        return;
    }
    window.ProductClickTrackingInitialized = true;
    
    // Track clicks on product links and buttons
    function trackProductClick(productId, action = 'click') {
        console.log('trackProductClick called with:', productId, action);
        if (!productId) {
            console.log('No product ID provided');
            return;
        }
        
        console.log('Sending tracking request to /product/track/click/' + productId);
        // Send tracking request using GET to avoid CSRF issues
        fetch('/product/track/click/' + productId, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Product click tracked:', productId);
            }
        })
        .catch(error => {
            console.warn('Failed to track product click:', error);
        });
    }
    
    // Track clicks on product links
    function trackProductLinks() {
        console.log('Product click tracking initialized');
        
        // Track clicks on product title links (only image links, not buttons)
        document.addEventListener('click', function(e) {
            const productLink = e.target.closest('a[href*="/products/"]');
            if (productLink && 
                !productLink.closest('.action_links') && 
                !productLink.classList.contains('add_to_cart_btn') &&
                !productLink.classList.contains('modalQuickView')) {
                console.log('Product link clicked:', productLink);
                const href = productLink.getAttribute('href');
                const productId = productLink.getAttribute('data-product-id') || 
                                productLink.getAttribute('id') ||
                                extractProductIdFromUrl(href);
                
                console.log('Product ID found:', productId);
                if (productId) {
                    trackProductClick(productId, 'product_link_click');
                }
            }
        });
        
        // Track clicks on quick view buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.modalQuickView')) {
                console.log('Quick view button clicked');
                const quickViewBtn = e.target.closest('.modalQuickView');
                const productId = quickViewBtn.getAttribute('id') || 
                                quickViewBtn.getAttribute('data-product-id');
                
                console.log('Quick view Product ID:', productId);
                if (productId) {
                    trackProductClick(productId, 'quick_view_click');
                }
            }
        });
        
        // Track clicks on add to cart buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add_to_cart_btn, .add-to-cart-btn, .cart-btn')) {
                console.log('Add to cart button clicked');
                const cartBtn = e.target.closest('.add_to_cart_btn, .add-to-cart-btn, .cart-btn');
                const productId = cartBtn.getAttribute('data-product-id') || 
                                cartBtn.getAttribute('id');
                
                console.log('Add to cart Product ID:', productId);
                if (productId) {
                    trackProductClick(productId, 'add_to_cart_click');
                }
            }
        });
        
        // Track clicks on wishlist buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.wishlist-btn, .wishlist_button a')) {
                const wishlistBtn = e.target.closest('.wishlist-btn, .wishlist_button a');
                const productId = wishlistBtn.getAttribute('data-product-id') || 
                                wishlistBtn.getAttribute('id');
                
                if (productId) {
                    trackProductClick(productId, 'wishlist_click');
                }
            }
        });
        
        // Track clicks on compare buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.compare-btn, .compare_button a')) {
                const compareBtn = e.target.closest('.compare-btn, .compare_button a');
                const productId = compareBtn.getAttribute('data-product-id') || 
                                compareBtn.getAttribute('id');
                
                if (productId) {
                    trackProductClick(productId, 'compare_click');
                }
            }
        });
    }
    
    // Extract product ID from URL
    function extractProductIdFromUrl(url) {
        if (!url) return null;
        
        // Try to extract from /products/{slug} pattern
        const match = url.match(/\/products\/([^\/\?]+)/);
        if (match) {
            // This is a slug, we'd need to look up the product ID
            // For now, we'll skip this case and rely on data attributes
            return null;
        }
        
        return null;
    }
    
    // Initialize tracking when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', trackProductLinks);
    } else {
        trackProductLinks();
    }
    
    // Make trackProductClick available globally for manual tracking
    window.trackProductClick = trackProductClick;
    
})();

/**
 * Cart Component - Reusable Cart Management
 * Usage: Include this file and initialize with new CartComponent()
 */
class CartComponent {
    constructor(options = {}) {
        this.apiBase = options.apiBase || '/api/cart';
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.onUpdate = options.onUpdate || (() => {});
        this.onError = options.onError || (() => {});
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateCartCount();
    }

    bindEvents() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-add-to-cart]')) {
                e.preventDefault();
                this.addToCart(e.target);
            }
        });

        // Update quantity buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-cart-update]')) {
                e.preventDefault();
                this.updateCartItem(e.target);
            }
        });

        // Remove from cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-cart-remove]')) {
                e.preventDefault();
                this.removeFromCart(e.target);
            }
        });

        // Clear cart button
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-cart-clear]')) {
                e.preventDefault();
                this.clearCart();
            }
        });
    }

    async addToCart(button) {
        const productId = button.getAttribute('data-product-id');
        const quantity = button.getAttribute('data-quantity') || 1;
        const attributes = this.getAttributes(button);

        if (!productId) {
            this.onError('Product ID is required');
            return;
        }

        this.setLoading(button, true);

        try {
            const response = await fetch(`${this.apiBase}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: parseInt(productId),
                    quantity: parseInt(quantity),
                    attributes: attributes
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount();
                this.onUpdate('add', data);
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to add item to cart', 'error');
            this.onError(error);
        } finally {
            this.setLoading(button, false);
        }
    }

    async updateCartItem(button) {
        const cartKey = button.getAttribute('data-cart-key');
        const quantity = button.getAttribute('data-quantity') || button.closest('.cart-item')?.querySelector('input[type="number"]')?.value;

        if (!cartKey || !quantity) {
            this.onError('Cart key and quantity are required');
            return;
        }

        this.setLoading(button, true);

        try {
            const response = await fetch(`${this.apiBase}/${cartKey}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: parseInt(quantity)
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount();
                this.onUpdate('update', data);
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to update cart item', 'error');
            this.onError(error);
        } finally {
            this.setLoading(button, false);
        }
    }

    async removeFromCart(button) {
        const cartKey = button.getAttribute('data-cart-key');

        if (!cartKey) {
            this.onError('Cart key is required');
            return;
        }

        if (!confirm('Are you sure you want to remove this item from cart?')) {
            return;
        }

        this.setLoading(button, true);

        try {
            const response = await fetch(`${this.apiBase}/${cartKey}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount();
                this.onUpdate('remove', data);
                
                // Remove item from DOM
                const cartItem = button.closest('.cart-item');
                if (cartItem) {
                    cartItem.remove();
                }
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to remove item from cart', 'error');
            this.onError(error);
        } finally {
            this.setLoading(button, false);
        }
    }

    async clearCart() {
        if (!confirm('Are you sure you want to clear your cart?')) {
            return;
        }

        try {
            const response = await fetch(`${this.apiBase}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.updateCartCount();
                this.onUpdate('clear', data);
                
                // Clear cart items from DOM
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach(item => item.remove());
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to clear cart', 'error');
            this.onError(error);
        }
    }

    async getCartData() {
        try {
            const response = await fetch(`${this.apiBase}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            return data.success ? data.data : null;
        } catch (error) {
            this.onError(error);
            return null;
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch(`${this.apiBase}/count`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Update cart count in header
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(element => {
                    element.textContent = data.count;
                    element.style.display = data.count > 0 ? 'inline' : 'none';
                });
            }
        } catch (error) {
            console.error('Failed to update cart count:', error);
        }
    }

    getAttributes(button) {
        const attributes = {};
        const attributeInputs = button.closest('form')?.querySelectorAll('[data-attribute]');
        
        if (attributeInputs) {
            attributeInputs.forEach(input => {
                const key = input.getAttribute('data-attribute');
                const value = input.type === 'checkbox' ? input.checked : input.value;
                if (value) {
                    attributes[key] = value;
                }
            });
        }
        
        return attributes;
    }

    setLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.setAttribute('data-original-text', button.textContent);
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        } else {
            button.disabled = false;
            button.textContent = button.getAttribute('data-original-text') || button.textContent;
        }
    }

    showMessage(message, type = 'info') {
        // Create or update message element
        let messageElement = document.querySelector('.cart-message');
        
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.className = 'cart-message alert';
            document.body.appendChild(messageElement);
        }

        messageElement.className = `cart-message alert alert-${type} alert-dismissible fade show`;
        messageElement.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

        // Auto hide after 5 seconds
        setTimeout(() => {
            if (messageElement) {
                messageElement.remove();
            }
        }, 5000);
    }
}

// Auto-initialize if DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cartComponent = new CartComponent();
    });
} else {
    window.cartComponent = new CartComponent();
}


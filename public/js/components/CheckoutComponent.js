/**
 * Checkout Component - Reusable Checkout Management
 * Usage: Include this file and initialize with new CheckoutComponent()
 */
class CheckoutComponent {
    constructor(options = {}) {
        this.apiBase = options.apiBase || '/api/checkout';
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.onSuccess = options.onSuccess || (() => {});
        this.onError = options.onError || (() => {});
        this.onValidationError = options.onValidationError || (() => {});
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCheckoutData();
    }

    bindEvents() {
        // Checkout form submission
        document.addEventListener('submit', (e) => {
            if (e.target.matches('#checkout-form')) {
                e.preventDefault();
                this.processCheckout(e.target);
            }
        });

        // Address form submission
        document.addEventListener('submit', (e) => {
            if (e.target.matches('#address-form')) {
                e.preventDefault();
                this.saveAddress(e.target);
            }
        });

        // Payment method selection
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[name="payment_method"]')) {
                this.updatePaymentMethod(e.target.value);
            }
        });

        // Shipping method selection
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[name="shipping_method"]')) {
                this.updateShippingMethod(e.target.value);
            }
        });

        // Use saved address
        document.addEventListener('change', (e) => {
            if (e.target.matches('select[name="saved_address"]')) {
                this.useSavedAddress(e.target.value);
            }
        });
    }

    async loadCheckoutData() {
        try {
            const response = await fetch(`${this.apiBase}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.renderCheckoutData(data.data);
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to load checkout data', 'error');
            this.onError(error);
        }
    }

    async processCheckout(form) {
        const formData = new FormData(form);
        const checkoutData = Object.fromEntries(formData.entries());

        // Validate form
        const validation = this.validateCheckoutForm(checkoutData);
        if (!validation.valid) {
            this.showValidationErrors(validation.errors);
            this.onValidationError(validation.errors);
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        this.setLoading(submitButton, true);

        try {
            const response = await fetch(`${this.apiBase}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(checkoutData)
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.onSuccess(data);

                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.payment_options) {
                    // Handle payment gateway redirect
                    this.handlePaymentRedirect(data.payment_options);
                }
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Checkout process failed', 'error');
            this.onError(error);
        } finally {
            this.setLoading(submitButton, false);
        }
    }

    async saveAddress(form) {
        const formData = new FormData(form);
        const addressData = Object.fromEntries(formData.entries());

        const submitButton = form.querySelector('button[type="submit"]');
        this.setLoading(submitButton, true);

        try {
            const response = await fetch(`${this.apiBase}/address`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(addressData)
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.loadCheckoutData(); // Reload to show new address
            } else {
                this.showMessage(data.message, 'error');
                this.onError(data.message);
            }
        } catch (error) {
            this.showMessage('Failed to save address', 'error');
            this.onError(error);
        } finally {
            this.setLoading(submitButton, false);
        }
    }

    async useSavedAddress(addressId) {
        if (!addressId) return;

        try {
            const response = await fetch(`${this.apiBase}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success && data.data.addresses) {
                const address = data.data.addresses.find(addr => addr.id == addressId);
                if (address) {
                    this.fillAddressForm(address);
                }
            }
        } catch (error) {
            this.onError(error);
        }
    }

    fillAddressForm(address) {
        const form = document.querySelector('#checkout-form');
        if (!form) return;

        const fields = [
            'customer_name', 'customer_phone', 'customer_address',
            'customer_city', 'customer_state', 'customer_postcode'
        ];

        fields.forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input && address[field.replace('customer_', '')]) {
                input.value = address[field.replace('customer_', '')];
            }
        });
    }

    updatePaymentMethod(method) {
        const paymentDetails = document.querySelector('.payment-details');
        if (!paymentDetails) return;

        // Hide all payment method details
        const allDetails = paymentDetails.querySelectorAll('.payment-method-detail');
        allDetails.forEach(detail => detail.style.display = 'none');

        // Show selected payment method details
        const selectedDetail = paymentDetails.querySelector(`[data-method="${method}"]`);
        if (selectedDetail) {
            selectedDetail.style.display = 'block';
        }
    }

    updateShippingMethod(method) {
        // Update shipping cost and total
        this.calculateTotals();
    }

    calculateTotals() {
        const subtotal = parseFloat(document.querySelector('[data-subtotal]')?.textContent || 0);
        const shipping = parseFloat(document.querySelector('[data-shipping]')?.textContent || 0);
        const tax = parseFloat(document.querySelector('[data-tax]')?.textContent || 0);
        const discount = parseFloat(document.querySelector('[data-discount]')?.textContent || 0);

        const total = subtotal + shipping + tax - discount;

        const totalElement = document.querySelector('[data-total]');
        if (totalElement) {
            totalElement.textContent = total.toFixed(2);
        }
    }

    validateCheckoutForm(data) {
        const errors = {};
        const required = [
            'customer_name', 'customer_email', 'customer_phone',
            'customer_address', 'payment_method'
        ];

        required.forEach(field => {
            if (!data[field] || data[field].trim() === '') {
                errors[field] = `${this.getFieldLabel(field)} is required`;
            }
        });

        // Email validation
        if (data.customer_email && !this.isValidEmail(data.customer_email)) {
            errors.customer_email = 'Please enter a valid email address';
        }

        // Phone validation
        if (data.customer_phone && !this.isValidPhone(data.customer_phone)) {
            errors.customer_phone = 'Please enter a valid phone number';
        }

        return {
            valid: Object.keys(errors).length === 0,
            errors: errors
        };
    }

    showValidationErrors(errors) {
        // Clear previous errors
        document.querySelectorAll('.field-error').forEach(error => error.remove());
        document.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));

        // Show new errors
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'field-error text-danger small mt-1';
                errorElement.textContent = errors[field];
                
                input.parentNode.appendChild(errorElement);
            }
        });
    }

    renderCheckoutData(data) {
        // Render cart items
        this.renderCartItems(data.cart.items);

        // Render addresses
        this.renderAddresses(data.addresses);

        // Render payment methods
        this.renderPaymentMethods(data.payment_methods);

        // Render shipping methods
        this.renderShippingMethods(data.shipping_methods);

        // Update totals
        this.updateTotals(data.cart);
    }

    renderCartItems(items) {
        const container = document.querySelector('.checkout-items');
        if (!container) return;

        container.innerHTML = items.map(item => `
            <div class="checkout-item d-flex align-items-center mb-3">
                <img src="${item.image}" alt="${item.name}" class="item-image me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div class="item-details flex-grow-1">
                    <h6 class="mb-1">${item.name}</h6>
                    <small class="text-muted">Qty: ${item.quantity} × ${item.price}</small>
                </div>
                <div class="item-total">
                    <strong>${item.total}</strong>
                </div>
            </div>
        `).join('');
    }

    renderAddresses(addresses) {
        const container = document.querySelector('.saved-addresses');
        if (!container) return;

        if (addresses.length === 0) {
            container.innerHTML = '<p class="text-muted">No saved addresses</p>';
            return;
        }

        container.innerHTML = `
            <select name="saved_address" class="form-control">
                <option value="">Select a saved address</option>
                ${addresses.map(addr => `
                    <option value="${addr.id}">${addr.name} - ${addr.address}</option>
                `).join('')}
            </select>
        `;
    }

    renderPaymentMethods(methods) {
        const container = document.querySelector('.payment-methods');
        if (!container) return;

        container.innerHTML = Object.keys(methods).map(key => {
            const method = methods[key];
            return `
                <div class="payment-method">
                    <input type="radio" name="payment_method" value="${key}" id="payment_${key}" ${key === 'ssl' ? 'checked' : ''}>
                    <label for="payment_${key}" class="payment-method-label">
                        <i class="${method.icon}"></i>
                        <span class="method-name">${method.name}</span>
                        <span class="method-description">${method.description}</span>
                    </label>
                </div>
            `;
        }).join('');
    }

    renderShippingMethods(methods) {
        const container = document.querySelector('.shipping-methods');
        if (!container) return;

        container.innerHTML = Object.keys(methods).map(key => {
            const method = methods[key];
            return `
                <div class="shipping-method">
                    <input type="radio" name="shipping_method" value="${key}" id="shipping_${key}" ${key === 'standard' ? 'checked' : ''}>
                    <label for="shipping_${key}" class="shipping-method-label">
                        <span class="method-name">${method.name}</span>
                        <span class="method-description">${method.description}</span>
                        <span class="method-cost">${method.cost} BDT</span>
                    </label>
                </div>
            `;
        }).join('');
    }

    updateTotals(cart) {
        const elements = {
            subtotal: document.querySelector('[data-subtotal]'),
            shipping: document.querySelector('[data-shipping]'),
            tax: document.querySelector('[data-tax]'),
            discount: document.querySelector('[data-discount]'),
            total: document.querySelector('[data-total]')
        };

        if (elements.subtotal) elements.subtotal.textContent = cart.subtotal.toFixed(2);
        if (elements.shipping) elements.shipping.textContent = cart.shipping.toFixed(2);
        if (elements.tax) elements.tax.textContent = cart.tax.toFixed(2);
        if (elements.discount) elements.discount.textContent = cart.discount.toFixed(2);
        if (elements.total) elements.total.textContent = cart.grand_total.toFixed(2);
    }

    handlePaymentRedirect(paymentOptions) {
        // This would handle SSL Commerz or other payment gateway redirects
        if (paymentOptions.GatewayPageURL) {
            window.location.href = paymentOptions.GatewayPageURL;
        }
    }

    getFieldLabel(field) {
        const labels = {
            customer_name: 'Name',
            customer_email: 'Email',
            customer_phone: 'Phone',
            customer_address: 'Address',
            customer_city: 'City',
            customer_state: 'State',
            customer_postcode: 'Postcode',
            payment_method: 'Payment Method'
        };
        return labels[field] || field;
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isValidPhone(phone) {
        return /^[0-9+\-\s()]+$/.test(phone);
    }

    setLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.setAttribute('data-original-text', button.textContent);
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        } else {
            button.disabled = false;
            button.textContent = button.getAttribute('data-original-text') || button.textContent;
        }
    }

    showMessage(message, type = 'info') {
        let messageElement = document.querySelector('.checkout-message');
        
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.className = 'checkout-message alert';
            document.querySelector('.checkout-container')?.appendChild(messageElement) || document.body.appendChild(messageElement);
        }

        messageElement.className = `checkout-message alert alert-${type} alert-dismissible fade show`;
        messageElement.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

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
        window.checkoutComponent = new CheckoutComponent();
    });
} else {
    window.checkoutComponent = new CheckoutComponent();
}


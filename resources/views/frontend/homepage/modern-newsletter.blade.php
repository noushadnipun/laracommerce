<!-- Modern Newsletter Section -->
<section class="modern-newsletter-section">
    <div class="newsletter-container">
        <div class="newsletter-content">
            <!-- Left Side - Content -->
            <div class="newsletter-info" data-aos="fade-right">
                <div class="newsletter-badge">
                    <span class="badge-icon">📧</span>
                    <span class="badge-text">Stay Updated</span>
                </div>
                <h2 class="newsletter-title">Never Miss a Deal!</h2>
                <p class="newsletter-subtitle">
                    Subscribe to our newsletter and be the first to know about new products, 
                    exclusive offers, and special discounts. Join thousands of happy customers 
                    who stay ahead of the curve.
                </p>
                <div class="newsletter-features">
                    <div class="feature-item">
                        <i class="fa fa-check-circle"></i>
                        <span>Exclusive deals & discounts</span>
                    </div>
                    <div class="feature-item">
                        <i class="fa fa-check-circle"></i>
                        <span>Early access to new products</span>
                    </div>
                    <div class="feature-item">
                        <i class="fa fa-check-circle"></i>
                        <span>Weekly fashion tips & trends</span>
                    </div>
                    <div class="feature-item">
                        <i class="fa fa-check-circle"></i>
                        <span>No spam, unsubscribe anytime</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Form -->
            <div class="newsletter-form-container" data-aos="fade-left">
                <div class="newsletter-form-wrapper">
                    <h3 class="form-title">Subscribe Now</h3>
                    <p class="form-subtitle">Get 10% off your first order!</p>
                    
                    <form class="newsletter-form" id="newsletterForm">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       class="form-input" 
                                       placeholder="Enter your email address"
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       class="form-input" 
                                       placeholder="Your name (optional)">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="terms" required>
                                <span class="checkmark"></span>
                                <span class="checkbox-text">
                                    I agree to receive marketing emails and accept the 
                                    <a href="#" class="terms-link">Terms of Service</a> and 
                                    <a href="#" class="terms-link">Privacy Policy</a>
                                </span>
                            </label>
                        </div>
                        
                        <button type="submit" class="subscribe-btn">
                            <span class="btn-text">Subscribe Now</span>
                            <span class="btn-icon">
                                <i class="fa fa-paper-plane"></i>
                            </span>
                            <span class="btn-loading">
                                <i class="fa fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </form>
                    
                    <div class="newsletter-stats">
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Subscribers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4.8</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">99%</div>
                            <div class="stat-label">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Background Decoration -->
        <div class="newsletter-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
            <div class="decoration-dots">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Newsletter Section Styles */
.modern-newsletter-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #242424 0%, #354b65 100%);
    position: relative;
    overflow: hidden;
}

.modern-newsletter-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.newsletter-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.newsletter-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.newsletter-info {
    color: white;
}

.newsletter-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 30px;
    border: 1px solid rgba(255,255,255,0.2);
}

.newsletter-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 25px;
    background: linear-gradient(135deg, #ffffff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.newsletter-subtitle {
    font-size: 1.2rem;
    line-height: 1.8;
    margin-bottom: 40px;
    opacity: 0.9;
    max-width: 500px;
}

.newsletter-features {
    display: grid;
    gap: 15px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1rem;
    opacity: 0.9;
}

.feature-item i {
    color: #4ecdc4;
    font-size: 18px;
    width: 20px;
    text-align: center;
}

.newsletter-form-container {
    background: white;
    border-radius: 25px;
    padding: 50px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.newsletter-form-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(78, 205, 196, 0.05), rgba(102, 126, 234, 0.05));
    z-index: 1;
}

.newsletter-form-wrapper {
    position: relative;
    z-index: 2;
}

.form-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
    text-align: center;
}

.form-subtitle {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 40px;
    text-align: center;
}

.newsletter-form {
    margin-bottom: 40px;
}

.form-group {
    margin-bottom: 25px;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 20px;
    z-index: 3;
    color: #6c757d;
    font-size: 16px;
}

.form-input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-input:focus {
    outline: none;
    border-color: #0063d1;
    background: white;
    box-shadow: 0 0 0 3px rgba(0, 99, 209, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    font-size: 14px;
    line-height: 1.5;
    color: #6c757d;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #e9ecef;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
    margin-top: 2px;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #0063d1;
    border-color: #0063d1;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.terms-link {
    color: #0063d1;
    text-decoration: none;
    font-weight: 600;
}

.terms-link:hover {
    text-decoration: underline;
}

.subscribe-btn {
    width: 100%;
    padding: 18px 30px;
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}

.subscribe-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.subscribe-btn:hover::before {
    left: 100%;
}

.subscribe-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 99, 209, 0.4);
}

.subscribe-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-loading {
    display: none;
}

.subscribe-btn.loading .btn-text,
.subscribe-btn.loading .btn-icon {
    display: none;
}

.subscribe-btn.loading .btn-loading {
    display: block;
}

.newsletter-stats {
    display: flex;
    justify-content: space-around;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    color: #2c3e50;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Background Decoration */
.newsletter-decoration {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 1;
}

.decoration-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    animation: float 6s ease-in-out infinite;
}

.circle-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.circle-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.circle-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

.decoration-dots {
    position: absolute;
    top: 30%;
    right: 30%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.decoration-dots span {
    width: 8px;
    height: 8px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

.decoration-dots span:nth-child(2) {
    animation-delay: 0.5s;
}

.decoration-dots span:nth-child(3) {
    animation-delay: 1s;
}

.decoration-dots span:nth-child(4) {
    animation-delay: 1.5s;
}

.decoration-dots span:nth-child(5) {
    animation-delay: 2s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.8; }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modern-newsletter-section {
        padding: 80px 0;
    }
    
    .newsletter-content {
        grid-template-columns: 1fr;
        gap: 50px;
    }
    
    .newsletter-title {
        font-size: 2.5rem;
    }
    
    .newsletter-subtitle {
        font-size: 1rem;
    }
    
    .newsletter-form-container {
        padding: 30px 20px;
    }
    
    .form-title {
        font-size: 1.5rem;
    }
    
    .newsletter-stats {
        flex-direction: column;
        gap: 20px;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
}

@media (max-width: 480px) {
    .modern-newsletter-section {
        padding: 60px 0;
    }
    
    .newsletter-title {
        font-size: 2rem;
    }
    
    .newsletter-form-container {
        padding: 25px 15px;
    }
    
    .form-input {
        padding: 12px 15px 12px 45px;
        font-size: 14px;
    }
    
    .subscribe-btn {
        padding: 15px 25px;
        font-size: 14px;
    }
}
</style>

<script>
$(document).ready(function() {
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var button = form.find('.subscribe-btn');
        var email = form.find('input[name="email"]').val();
        var name = form.find('input[name="name"]').val();
        var terms = form.find('input[name="terms"]').is(':checked');
        
        // Validation
        if (!email) {
            ElegantNotification.error('Please enter your email address');
            return;
        }
        
        if (!terms) {
            ElegantNotification.error('Please accept the terms and conditions');
            return;
        }
        
        // Show loading state
        button.addClass('loading').prop('disabled', true);
        
        // Simulate API call (replace with actual newsletter subscription logic)
        setTimeout(function() {
            // Success simulation
            ElegantNotification.success('Thank you for subscribing! Check your email for confirmation.');
            form[0].reset();
            button.removeClass('loading').prop('disabled', false);
        }, 2000);
        
        // Actual implementation would be:
        /*
        $.ajax({
            type: 'POST',
            url: '/newsletter/subscribe',
            data: {
                email: email,
                name: name,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    ElegantNotification.success(response.message);
                    form[0].reset();
                } else {
                    ElegantNotification.error(response.message);
                }
            },
            error: function(xhr) {
                ElegantNotification.error('Something went wrong. Please try again.');
            },
            complete: function() {
                button.removeClass('loading').prop('disabled', false);
            }
        });
        */
    });
    
    // Add floating animation to decoration elements
    $('.decoration-circle').each(function(index) {
        $(this).css('animation-delay', (index * 2) + 's');
    });
});
</script>

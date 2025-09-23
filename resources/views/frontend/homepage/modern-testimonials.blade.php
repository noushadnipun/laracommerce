@php
    // Sample testimonials data - you can replace this with real data from your database
    $testimonials = [
        [
            'name' => 'Sarah Johnson',
            'role' => 'Fashion Blogger',
            'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'Amazing quality products and super fast delivery! I\'ve been shopping here for months and never disappointed.',
            'product' => 'Summer Collection'
        ],
        [
            'name' => 'Michael Chen',
            'role' => 'Tech Enthusiast',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'The customer service is outstanding. They helped me find exactly what I was looking for.',
            'product' => 'Electronics'
        ],
        [
            'name' => 'Emily Rodriguez',
            'role' => 'Home Decor Lover',
            'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'Beautiful products at great prices. The packaging was so elegant, it felt like a luxury experience.',
            'product' => 'Home & Living'
        ],
        [
            'name' => 'David Thompson',
            'role' => 'Fitness Coach',
            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'Fast shipping and excellent quality. I recommend this store to all my friends and family.',
            'product' => 'Sports & Fitness'
        ],
        [
            'name' => 'Lisa Wang',
            'role' => 'Art Director',
            'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'The variety of products is incredible. I always find something unique and special here.',
            'product' => 'Art & Crafts'
        ],
        [
            'name' => 'James Wilson',
            'role' => 'Business Owner',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face',
            'rating' => 5,
            'comment' => 'Professional service and top-notch products. This is my go-to store for all my needs.',
            'product' => 'Business Supplies'
        ]
    ];
@endphp

<!-- Modern Testimonials Section -->
<section class="modern-testimonials-section">
    <div class="testimonials-container">
        <!-- Section Header -->
        <div class="testimonials-header" data-aos="fade-up">
            <div class="section-badge">
                <span class="badge-icon">💬</span>
                <span class="badge-text">Customer Reviews</span>
            </div>
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-subtitle">Don't just take our word for it - hear from our satisfied customers</p>
            <div class="section-decoration">
                <div class="decoration-line"></div>
                <div class="decoration-dot"></div>
                <div class="decoration-line"></div>
            </div>
        </div>
        
        <!-- Testimonials Slider -->
        <div class="testimonials-slider-container" data-aos="fade-up" data-aos-delay="200">
            <div class="testimonials-slider owl-carousel">
                @foreach($testimonials as $index => $testimonial)
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fa fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">"{{ $testimonial['comment'] }}"</p>
                        <div class="testimonial-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $testimonial['rating'] ? 'active' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" 
                                 onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'">
                        </div>
                        <div class="author-info">
                            <h4 class="author-name">{{ $testimonial['name'] }}</h4>
                            <p class="author-role">{{ $testimonial['role'] }}</p>
                            <span class="author-product">{{ $testimonial['product'] }}</span>
                        </div>
                    </div>
                    
                    <div class="testimonial-decoration">
                        <div class="decoration-circle"></div>
                        <div class="decoration-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Slider Navigation -->
            <div class="slider-navigation">
                <button class="nav-btn prev-btn">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="nav-btn next-btn">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Slider Indicators -->
            <div class="slider-indicators">
                @foreach($testimonials as $index => $testimonial)
                <button class="indicator {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
        
        <!-- Stats Section -->
        <div class="testimonials-stats" data-aos="fade-up" data-aos-delay="400">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Average Rating</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Customer Support</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Testimonials Section Styles */
.modern-testimonials-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #0063d1 0%, #354b65 100%);
    position: relative;
    overflow: hidden;
}

.modern-testimonials-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    opacity: 0.3;
}

.testimonials-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.testimonials-header {
    text-align: center;
    margin-bottom: 80px;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.3);
}

.section-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.section-subtitle {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.section-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.decoration-line {
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
}

.decoration-dot {
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
}

.testimonials-slider-container {
    position: relative;
    margin-bottom: 80px;
}

.testimonials-slider {
    margin: 0 60px;
}

.testimonial-card {
    background: white;
    border-radius: 25px;
    padding: 40px;
    margin: 0 15px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.testimonial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.testimonial-card:hover::before {
    opacity: 1;
}

.testimonial-content {
    position: relative;
    z-index: 2;
    margin-bottom: 30px;
}

.quote-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #0063d1, #354b65);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(0, 99, 209, 0.3);
}

.quote-icon i {
    font-size: 24px;
    color: white;
}

.testimonial-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2c3e50;
    margin-bottom: 20px;
    font-style: italic;
}

.testimonial-rating {
    display: flex;
    gap: 3px;
}

.testimonial-rating i {
    font-size: 16px;
    color: #ddd;
    transition: color 0.3s ease;
}

.testimonial-rating i.active {
    color: #ffc107;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 2;
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #0063d1;
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.3);
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.author-info {
    flex: 1;
}

.author-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.author-role {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.author-product {
    font-size: 0.8rem;
    color: #0063d1;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.testimonial-decoration {
    position: absolute;
    top: 20px;
    right: 20px;
    opacity: 0.1;
}

.decoration-circle {
    width: 40px;
    height: 40px;
    border: 2px solid #0063d1;
    border-radius: 50%;
    position: relative;
}

.decoration-circle::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    background: #0063d1;
    border-radius: 50%;
}

.decoration-dots {
    position: absolute;
    top: 50px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.decoration-dots span {
    width: 6px;
    height: 6px;
    background: #0063d1;
    border-radius: 50%;
    opacity: 0.6;
}

.decoration-dots span:nth-child(2) {
    margin-left: 10px;
}

.decoration-dots span:nth-child(3) {
    margin-left: 20px;
}

/* Slider Navigation */
.slider-navigation {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
}

.nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    border: none;
    color: #667eea;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    pointer-events: all;
}

.nav-btn:hover {
    background: #0063d1;
    color: white;
    transform: scale(1.1);
}

/* Slider Indicators */
.slider-indicators {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
}

.indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active,
.indicator:hover {
    background: white;
    transform: scale(1.2);
}

/* Stats Section */
.testimonials-stats {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 40px;
    border: 1px solid rgba(255,255,255,0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
}

.stat-item {
    text-align: center;
    color: white;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #ffffff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    font-size: 1rem;
    font-weight: 600;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modern-testimonials-section {
        padding: 80px 0;
    }
    
    .section-title {
        font-size: 2.5rem;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
    }
    
    .testimonials-slider {
        margin: 0 20px;
    }
    
    .testimonial-card {
        padding: 30px 20px;
        margin: 0 10px;
    }
    
    .testimonial-text {
        font-size: 1rem;
    }
    
    .testimonial-author {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .slider-navigation {
        display: none;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
}

@media (max-width: 480px) {
    .modern-testimonials-section {
        padding: 60px 0;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .testimonial-card {
        padding: 25px 15px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Initialize testimonials slider
    $('.testimonials-slider').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        nav: false,
        dots: false,
        margin: 30,
        responsive: {
            768: {
                items: 2
            },
            1024: {
                items: 3
            }
        }
    });
    
    // Custom navigation
    $('.prev-btn').click(function() {
        $('.testimonials-slider').trigger('prev.owl.carousel');
    });
    
    $('.next-btn').click(function() {
        $('.testimonials-slider').trigger('next.owl.carousel');
    });
    
    // Custom indicators
    $('.indicator').click(function() {
        var slideIndex = $(this).data('slide');
        $('.testimonials-slider').trigger('to.owl.carousel', [slideIndex, 300]);
    });
    
    // Update active indicator
    $('.testimonials-slider').on('changed.owl.carousel', function(event) {
        $('.indicator').removeClass('active');
        $('.indicator').eq(event.item.index % $('.indicator').length).addClass('active');
    });
    
    // Add hover effects to testimonial cards
    $('.testimonial-card').hover(
        function() {
            $(this).find('.quote-icon').css('transform', 'scale(1.1) rotate(5deg)');
        },
        function() {
            $(this).find('.quote-icon').css('transform', 'scale(1) rotate(0deg)');
        }
    );
});
</script>

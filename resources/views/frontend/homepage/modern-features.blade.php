@php
    $promoStripsJson = \App\Helpers\WebsiteSettings::settings('promo_strips');
    $promoStrips = $promoStripsJson ? json_decode($promoStripsJson, true) : [];
    
    // Default features if no custom ones are set
    $defaultFeatures = [
        [
            'icon' => 'fa fa-shipping-fast',
            'title' => 'Free Shipping',
            'subtitle' => 'On orders over $50',
            'color' => '#0063d1'
        ],
        [
            'icon' => 'fa fa-undo',
            'title' => 'Easy Returns',
            'subtitle' => '30-day return policy',
            'color' => '#354b65'
        ],
        [
            'icon' => 'fa fa-shield-alt',
            'title' => 'Secure Payment',
            'subtitle' => '100% secure checkout',
            'color' => '#242424'
        ],
        [
            'icon' => 'fa fa-headset',
            'title' => '24/7 Support',
            'subtitle' => 'Always here to help',
            'color' => '#0063d1'
        ]
    ];
    
    $features = !empty($promoStrips) ? $promoStrips : $defaultFeatures;
@endphp

<!-- Modern Features Section -->
<section class="modern-features-section">
    <div class="features-container">
        <div class="features-grid">
            @foreach($features as $index => $feature)
            <div class="feature-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="feature-icon" style="--feature-color: {{ $feature['color'] ?? '#667eea' }}">
                    <i class="{{ $feature['icon'] ?? 'fa fa-star' }}"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">{{ $feature['title'] ?? 'Feature' }}</h3>
                    <p class="feature-subtitle">{{ $feature['subtitle'] ?? 'Description' }}</p>
                </div>
                <div class="feature-decoration">
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
    </div>
</section>

<style>
/* Modern Features Section Styles */
.modern-features-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f6fafb 0%, #f8f8f8 100%);
    position: relative;
    overflow: hidden;
}

.modern-features-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.features-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 2;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.feature-card {
    position: relative;
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    transition: transform 0.3s ease;
    overflow: hidden;
}


.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 25px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--feature-color), color-mix(in srgb, var(--feature-color) 80%, black));
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.feature-icon::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--feature-color), color-mix(in srgb, var(--feature-color) 60%, white));
    opacity: 0.3;
    z-index: -1;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon::before {
    transform: scale(1.1);
    opacity: 0.5;
}

.feature-icon i {
    font-size: 32px;
    color: white;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
}

.feature-card:hover .feature-icon i {
    transform: scale(1.1);
}

.feature-content {
    position: relative;
    z-index: 2;
}

.feature-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-title {
    color: var(--feature-color);
    transform: translateY(-2px);
}

.feature-subtitle {
    font-size: 1rem;
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-subtitle {
    color: #495057;
}

.feature-decoration {
    position: absolute;
    top: 20px;
    right: 20px;
    opacity: 0.1;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-decoration {
    opacity: 0.2;
    transform: scale(1.1);
}

.decoration-circle {
    width: 40px;
    height: 40px;
    border: 2px solid var(--feature-color);
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
    background: var(--feature-color);
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
    background: var(--feature-color);
    border-radius: 50%;
    opacity: 0.6;
}

.decoration-dots span:nth-child(2) {
    margin-left: 10px;
}

.decoration-dots span:nth-child(3) {
    margin-left: 20px;
}

/* Animation for feature cards */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.feature-card:nth-child(odd) {
    animation: float 6s ease-in-out infinite;
}

.feature-card:nth-child(even) {
    animation: float 6s ease-in-out infinite reverse;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modern-features-section {
        padding: 60px 0;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .feature-card {
        padding: 30px 20px;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 20px;
    }
    
    .feature-icon i {
        font-size: 24px;
    }
    
    .feature-title {
        font-size: 1.2rem;
    }
    
    .feature-subtitle {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .modern-features-section {
        padding: 40px 0;
    }
    
    .feature-card {
        padding: 25px 15px;
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
    }
    
    .feature-icon i {
        font-size: 20px;
    }
}
</style>

<script>
// Add AOS (Animate On Scroll) library if not already included
if (typeof AOS === 'undefined') {
    // Simple fallback animation
    $(document).ready(function() {
        $('.feature-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(30px)'
            });
            
            setTimeout(() => {
                $(this).animate({
                    'opacity': '1'
                }, 600).css('transform', 'translateY(0)');
            }, index * 100);
        });
    });
}
</script>

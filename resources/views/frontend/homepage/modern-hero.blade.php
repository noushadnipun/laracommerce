@php
    $heroSliders = \App\Helpers\WebsiteSettings::homeSlider();
    $rightBanners = \App\Helpers\WebsiteSettings::homeSliderRight();
@endphp

<!-- Modern Hero Section -->
<section class="modern-hero-section">
    <div class="hero-container">
        <div class="hero-grid">
            <!-- Main Hero Slider -->
            <div class="hero-main">
                <div class="hero-slider owl-carousel">
                    @foreach($heroSliders as $index => $data)
                    <div class="hero-slide" data-bgimg="{{App\Models\Media::fileLocation($data->featured_image)}}" 
                         style="background-image:url('{{ App\Models\Media::fileLocation($data->featured_image) }}');">
                        <div class="hero-content">
                            <div class="hero-badge">
                                <span class="badge-text">New Collection</span>
                            </div>
                            <h1 class="hero-title">
                                {!! $data->title ?? 'Discover Amazing Products' !!}
                            </h1>
                            <p class="hero-description">
                                {!! $data->description ?? 'Find the perfect products for your lifestyle' !!}
                            </p>
                            <div class="hero-actions">
                                <a href="{{ $data->slug ?? '#' }}" class="btn btn-primary btn-hero">
                                    <span>Shop Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                                <a href="#featured-products" class="btn btn-outline btn-hero">
                                    <span>Explore</span>
                                </a>
                            </div>
                        </div>
                        <div class="hero-overlay"></div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Hero Navigation -->
                <div class="hero-nav">
                    <button class="hero-nav-btn prev-btn">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button class="hero-nav-btn next-btn">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
                
                <!-- Hero Indicators -->
                <div class="hero-indicators">
                    @foreach($heroSliders as $index => $data)
                    <button class="hero-indicator {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Side Banners -->
            <div class="hero-sidebar">
                @foreach($rightBanners as $index => $data)
                <div class="hero-banner {{ $index === 0 ? 'banner-large' : 'banner-small' }}">
                    <a href="{{ $data->slug ?? '#' }}" class="banner-link">
                        <div class="banner-image">
                            <img src="{{App\Models\Media::fileLocation($data->featured_image)}}" alt="{{ $data->title ?? '' }}">
                        </div>
                        <div class="banner-content">
                            <h3 class="banner-title">{{ $data->title ?? 'Special Offer' }}</h3>
                            <p class="banner-subtitle">{{ $data->subtitle ?? 'Limited Time' }}</p>
                            @if($index === 0)
                            <span class="banner-cta">Shop Now <i class="fa fa-arrow-right"></i></span>
                            @endif
                        </div>
                        <div class="banner-overlay"></div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Hero Section Styles */
.modern-hero-section {
    position: relative;
    min-height: 80vh;
    background: linear-gradient(135deg, #0063d1 0%, #354b65 100%);
    overflow: hidden;
}

.hero-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    height: 100%;
}

.hero-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    height: 80vh;
    min-height: 600px;
}

.hero-main {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.hero-slide {
    position: relative;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    padding: 40px;
}

.hero-content {
    position: relative;
    z-index: 3;
    max-width: 600px;
    color: white;
}

.hero-badge {
    margin-bottom: 20px;
}

.badge-text {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border: 1px solid rgba(255,255,255,0.3);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #ffffff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.hero-description {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 30px;
    opacity: 0.9;
    max-width: 500px;
}

.hero-actions {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.btn-hero {
    padding: 15px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-primary.btn-hero {
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
    box-shadow: 0 8px 25px rgba(0, 99, 209, 0.4);
}

.btn-primary.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 99, 209, 0.6);
}

.btn-outline.btn-hero {
    background: rgba(255,255,255,0.1);
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.btn-outline.btn-hero:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
    transform: translateY(-3px);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(0,0,0,0.2));
    z-index: 1;
}

.hero-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.hero-banner {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.hero-banner:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.banner-large {
    flex: 2;
    min-height: 300px;
}

.banner-small {
    flex: 1;
    min-height: 150px;
}

.banner-link {
    display: block;
    height: 100%;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.banner-image {
    height: 100%;
    overflow: hidden;
}

.banner-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.hero-banner:hover .banner-image img {
    transform: scale(1.05);
}

.banner-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    color: white;
    z-index: 2;
}

.banner-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.banner-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.banner-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 1px;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.1), rgba(0,0,0,0.3));
    z-index: 1;
}

/* Hero Navigation */
.hero-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    z-index: 4;
}

.hero-nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-nav-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
}

/* Hero Indicators */
.hero-indicators {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 4;
}

.hero-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.hero-indicator.active,
.hero-indicator:hover {
    background: white;
    transform: scale(1.2);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hero-grid {
        grid-template-columns: 1fr;
        gap: 20px;
        height: auto;
    }
    
    .hero-sidebar {
        flex-direction: row;
        gap: 15px;
    }
    
    .banner-large,
    .banner-small {
        flex: 1;
        min-height: 120px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .hero-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-hero {
        padding: 12px 25px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .hero-slide {
        padding: 30px 20px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-actions {
        gap: 10px;
    }
    
    .btn-hero {
        padding: 10px 20px;
        font-size: 12px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Initialize hero slider
    $('.hero-slider').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        nav: false,
        dots: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn'
    });
    
    // Custom navigation
    $('.prev-btn').click(function() {
        $('.hero-slider').trigger('prev.owl.carousel');
    });
    
    $('.next-btn').click(function() {
        $('.hero-slider').trigger('next.owl.carousel');
    });
    
    // Custom indicators
    $('.hero-indicator').click(function() {
        var slideIndex = $(this).data('slide');
        $('.hero-slider').trigger('to.owl.carousel', [slideIndex, 300]);
    });
    
    // Update active indicator
    $('.hero-slider').on('changed.owl.carousel', function(event) {
        $('.hero-indicator').removeClass('active');
        $('.hero-indicator').eq(event.item.index).addClass('active');
    });
});
</script>

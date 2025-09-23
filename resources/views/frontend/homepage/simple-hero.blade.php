<!-- Simple Hero Section -->
<section class="simple-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Welcome to Our Store</h1>
                    <p class="hero-subtitle">Discover amazing products at great prices</p>
                    <div class="hero-buttons">
                        <a href="{{ route('frontend_products') }}" class="btn btn-primary btn-lg">Shop Now</a>
                        <a href="#features" class="btn btn-outline-primary btn-lg">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="{{ asset('public/frontend/images/hero-image.jpg') }}" 
                         alt="Hero Image" 
                         class="img-fluid"
                         onerror="this.src='{{ asset('public/frontend/images/no-images.svg') }}'">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.simple-hero-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #0063d1 0%, #354b65 100%);
    color: white;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: white;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.hero-buttons .btn {
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.2s ease;
}

.hero-buttons .btn:hover {
    transform: translateY(-2px);
}

.hero-image {
    text-align: center;
}

.hero-image img {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-buttons {
        justify-content: center;
    }
    
    .hero-buttons .btn {
        flex: 1;
        min-width: 120px;
    }
}
</style>

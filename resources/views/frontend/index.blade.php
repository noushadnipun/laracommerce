@extends('frontend.layouts.master')

@section('page-content')
@php
    // Original homepage sections
    $originalSections = [
        'slider' => 'frontend.homepage.slider',
        'promo_strips' => 'frontend.homepage.promo-strips',
        'product_showcase' => 'frontend.homepage.product-showcase',
        'new_arrivals' => 'frontend.homepage.new-arrivals',
        'on_sale' => 'frontend.homepage.on-sale',
        'brand_carousel' => 'frontend.homepage.brand',
        'recently_viewed' => 'frontend.homepage.recently-viewed',
        'seo_block' => 'frontend.homepage.seo-block',
    ];
    
    // Default original sections order
    $defaultOrder = [
        'slider',
        'promo_strips',
        'product_showcase',
        'new_arrivals',
        'on_sale',
        'brand_carousel',
        'recently_viewed',
        'seo_block'
    ];
    
    // Get custom order from settings or use default
    $sectionsOrder = \App\Helpers\WebsiteSettings::settings('homepage_sections_order');
    $sectionsOrder = $sectionsOrder ? json_decode($sectionsOrder, true) : $defaultOrder;
@endphp

<!-- Original Homepage -->
<div class="original-homepage">
    @foreach($sectionsOrder as $key)
        @if(isset($originalSections[$key]))
            @includeIf($originalSections[$key])
        @endif
    @endforeach
</div>

<script>
$(document).ready(function() {
    // Simple smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 500);
        }
    });
});
</script>

<style>
/* Global Original Homepage Styles */
.original-homepage {
    overflow-x: hidden;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #0063d1, #354b65);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0052b3, #2d3f52);
}

/* Animation classes */
.animate-in {
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading states */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

/* Focus styles for accessibility */
button:focus,
input:focus,
a:focus {
    outline: 2px solid #0063d1;
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .original-homepage {
        background: white !important;
    }
    
    .hero-slide,
    .testimonial-card,
    .newsletter-form-container {
        box-shadow: none !important;
    }
}
</style>

@endsection
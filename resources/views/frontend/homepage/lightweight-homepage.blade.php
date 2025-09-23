@extends('frontend.layouts.master')

@section('page-content')
@php
    // Lightweight homepage sections - minimal animations
    $lightweightSections = [
        'simple_hero' => 'frontend.homepage.simple-hero',
        'simple_features' => 'frontend.homepage.simple-features',
        'simple_products' => 'frontend.homepage.simple-products',
        'simple_newsletter' => 'frontend.homepage.simple-newsletter',
        // Keep some original sections for fallback
        'new_arrivals' => 'frontend.homepage.new-arrivals',
        'on_sale' => 'frontend.homepage.on-sale',
        'brand_carousel' => 'frontend.homepage.brand',
        'recently_viewed' => 'frontend.homepage.recently-viewed',
        'seo_block' => 'frontend.homepage.seo-block',
    ];
    
    // Default lightweight sections order
    $defaultOrder = [
        'simple_hero',
        'simple_features', 
        'simple_products',
        'new_arrivals',
        'on_sale',
        'brand_carousel',
        'simple_newsletter',
        'recently_viewed',
        'seo_block'
    ];
    
    // Get custom order from settings or use default
    $sectionsOrder = \App\Helpers\WebsiteSettings::settings('homepage_sections_order');
    $sectionsOrder = $sectionsOrder ? json_decode($sectionsOrder, true) : $defaultOrder;
@endphp

<!-- Lightweight Homepage -->
<div class="lightweight-homepage">
    @foreach($sectionsOrder as $key)
        @if(isset($lightweightSections[$key]))
            @includeIf($lightweightSections[$key])
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
            }, 300);
        }
    });
});
</script>

<style>
/* Lightweight Homepage Styles */
.lightweight-homepage {
    overflow-x: hidden;
}

/* Simple animations only */
.simple-fade-in {
    opacity: 0;
    animation: simpleFadeIn 0.6s ease-out forwards;
}

@keyframes simpleFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Simple hover effects */
.simple-hover {
    transition: transform 0.2s ease;
}

.simple-hover:hover {
    transform: translateY(-2px);
}

/* Focus styles for accessibility */
button:focus,
input:focus,
a:focus {
    outline: 2px solid #0063d1;
    outline-offset: 2px;
}
</style>

@endsection

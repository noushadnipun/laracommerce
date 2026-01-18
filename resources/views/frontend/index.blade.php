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

@endsection
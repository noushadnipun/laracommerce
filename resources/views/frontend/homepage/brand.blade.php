<div class="brand_area mb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section_title text-center mb-50">
                    <h2>Our Trusted Brands</h2>
                    <p>Shop from the world's most trusted brands</p>
                </div>
                <div class="brand_container owl-carousel">
                    @php 
                    $brands = \App\Models\ProductBrand::where('visibility', '1')
                                                    ->orderBy('created_at', 'DESC')
                                                    ->limit('20')->get() 
                    @endphp
                    
                    @if($brands->count() > 0)
                        @foreach($brands as $brand)
                        <div class="single_brand">
                            <a href="{{route('frontend_single_product_brand', $brand->slug)}}" title="{{$brand->name}}">
                                <img src="{{\App\Models\Media::fileLocation($brand->image)}}" 
                                     alt="{{$brand->name}}">
                            </a>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p>No brands available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Brand Section */
.brand_area {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.brand_area::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="brand-pattern" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%230063d1" opacity="0.02"/><circle cx="75" cy="75" r="1" fill="%23354b65" opacity="0.02"/></pattern></defs><rect width="100" height="100" fill="url(%23brand-pattern)"/></svg>');
    pointer-events: none;
}

.section_title h2 {
    font-size: 2.2rem;
    font-weight: 600;
    color: #242424;
    margin-bottom: 10px;
    position: relative;
}

.section_title h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 2px;
    background: #0063d1;
    border-radius: 1px;
}

.section_title p {
    color: #666;
    font-size: 1rem;
    margin: 0;
}

.brand_container {
    position: relative;
    z-index: 2;
}

.single_brand {
    text-align: center;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    margin: 0 10px;
    border: 1px solid rgba(0, 99, 209, 0.05);
}

.single_brand:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: rgba(0, 99, 209, 0.1);
}

.single_brand a {
    display: block;
    text-decoration: none;
    transition: all 0.3s ease;
}

.single_brand img {
    max-width: 100%;
    height: 60px;
    object-fit: contain;
    filter: grayscale(100%);
    transition: all 0.3s ease;
}

.single_brand:hover img {
    filter: grayscale(0%);
    transform: scale(1.05);
}

/* Owl Carousel Customization */
.brand_container .owl-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    z-index: 10;
}

.brand_container .owl-prev,
.brand_container .owl-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    border: 0px solid #0063d1;
    border-radius: 50%;
    color: #0063d1;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.brand_container .owl-prev {
    left: -25px;
}

.brand_container .owl-next {
    right: -25px;
}

.brand_container .owl-prev:hover,
.brand_container .owl-next:hover {
    background: #0063d1;
    color: white;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 99, 209, 0.4);
}

.brand_container .owl-dots {
    text-align: center;
    margin-top: 30px;
}

.brand_container .owl-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 99, 209, 0.3);
    margin: 0 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.brand_container .owl-dot.active {
    background: #0063d1;
    transform: scale(1.2);
}

.brand_container .owl-dot:hover {
    background: #0063d1;
    transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .brand_area {
        padding: 40px 0;
    }
    
    .section_title h2 {
        font-size: 1.8rem;
    }
    
    .single_brand {
        padding: 15px;
        margin: 0 5px;
    }
    
    .single_brand img {
        height: 50px;
    }
    
    .brand_container .owl-prev,
    .brand_container .owl-next {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .brand_container .owl-prev {
        left: -20px;
    }
    
    .brand_container .owl-next {
        right: -20px;
    }
}
</style>

<script>
$(document).ready(function() {
    // Initialize brand carousel
    $('.brand_container').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2
            },
            576: {
                items: 3
            },
            768: {
                items: 4
            },
            992: {
                items: 5
            },
            1200: {
                items: 6
            }
        }
    });
});
</script>
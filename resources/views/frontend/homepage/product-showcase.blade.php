@php $productShowCase = \App\Helpers\WebsiteSettings::homeProductShowCase() @endphp
@if(!empty($productShowCase))
    @foreach(\App\Helpers\WebsiteSettings::homeProductShowCase() as $categoryID)
    <section class="elegant-product-showcase mb-46">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="elegant-section-title">
                        <h2>{{\App\Models\ProductCategory::categoryName($categoryID)}}</h2>
                    </div>
                </div>
            </div> 
            <div class="product_slick product_slick_column5 elegant-carousel" id="product-carousel-{{ $categoryID }}">
                <?php 
                    if(!empty($categoryID)){
                        $query = \App\Models\Product::productByCatId($categoryID)->where('visibility', '1')->limit('12')->get();
                        echo \App\Helpers\Frontend\ProductView::view($query); 
                    }
                ?>
            </div> 
        </div>
    </section>
    @endforeach
@endif

<style>
/* Elegant Product Showcase Styles */
.elegant-product-showcase {
    padding: 60px 0;
    background: linear-gradient(135deg, #ffffff 0%, #f6fafb 100%);
    position: relative;
    overflow: hidden;
}

.elegant-product-showcase::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%230063d1" opacity="0.03"/><circle cx="75" cy="75" r="1" fill="%23354b65" opacity="0.03"/><circle cx="50" cy="10" r="0.5" fill="%23242424" opacity="0.02"/><circle cx="10" cy="50" r="0.5" fill="%230063d1" opacity="0.02"/><circle cx="90" cy="30" r="0.5" fill="%23354b65" opacity="0.02"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.elegant-section-title {
    text-align: left;
    margin-bottom: 40px;
    position: relative;
    z-index: 2;
}

.elegant-section-title h2 {
    font-size: 2.2rem;
    font-weight: 600;
    color: #242424;
    margin: 0;
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
}

.elegant-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background: #0063d1;
    border-radius: 1px;
}

.elegant-carousel {
    position: relative;
    z-index: 2;
    padding: 0 20px;
    opacity: 1;
    animation: fadeInUp 0.6s ease-out forwards;
}

.elegant-carousel .slick-slide {
    padding: 0 10px;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading Placeholder */
.loading-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    padding: 40px 20px;
    text-align: center;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #0063d1;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-placeholder p {
    color: #0063d1;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

/* Carousel Navigation Styles */
.elegant-carousel .slick-prev,
.elegant-carousel .slick-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #0063d1;
    border-radius: 50%;
    color: #0063d1;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.2);
    backdrop-filter: blur(10px);
}

.elegant-carousel .slick-prev {
    left: -25px;
}

.elegant-carousel .slick-next {
    right: -25px;
}

.elegant-carousel .slick-prev:hover,
.elegant-carousel .slick-next:hover {
    background: #0063d1;
    color: white;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 99, 209, 0.4);
}

.elegant-carousel .slick-prev:before,
.elegant-carousel .slick-next:before {
    content: '';
    display: none;
}

.elegant-carousel .slick-prev i,
.elegant-carousel .slick-next i {
    font-size: 16px;
    font-weight: bold;
}

/* Carousel Dots */
.elegant-carousel .slick-dots {
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.elegant-carousel .slick-dots li {
    margin: 0;
}

.elegant-carousel .slick-dots li button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 99, 209, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0;
}

.elegant-carousel .slick-dots li.slick-active button {
    background: #0063d1;
    transform: scale(1.2);
}

.elegant-carousel .slick-dots li button:hover {
    background: #0063d1;
    transform: scale(1.1);
}

/* Responsive Carousel */
@media (max-width: 768px) {
    .elegant-carousel .slick-prev,
    .elegant-carousel .slick-next {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .elegant-carousel .slick-prev {
        left: -20px;
    }
    
    .elegant-carousel .slick-next {
        right: -20px;
    }
    
    .elegant-carousel .slick-dots {
        bottom: -30px;
    }
    
    .elegant-carousel .slick-dots li button {
        width: 10px;
        height: 10px;
    }
}

/* Responsive Grid */
@media (min-width: 1200px) {
    .elegant-product-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .elegant-product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .elegant-product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 576px) and (max-width: 767px) {
    .elegant-product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575px) {
    .elegant-product-grid {
        grid-template-columns: 1fr;
    }
}


/* Enhanced Product Cards for Showcase */
.elegant-product-showcase .single_product {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 99, 209, 0.05);
    position: relative;
    margin: 15px 10px 30px 10px;
}

.elegant-product-showcase .single_product::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 99, 209, 0.02), rgba(53, 75, 101, 0.02));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
    pointer-events: none;
}

.elegant-product-showcase .single_product:hover::before {
    opacity: 1;
}

.elegant-product-showcase .single_product:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.08);
    border-color: rgba(0, 99, 209, 0.1);
}

.elegant-product-showcase .product_thumb {
    position: relative;
    overflow: hidden;
    border-radius: 15px 15px 0 0;
}

.elegant-product-showcase .product_thumb img {
    transition: transform 0.4s ease;
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.elegant-product-showcase .single_product:hover .product_thumb img {
    transform: scale(1.05);
}

.elegant-product-showcase .label_product {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 3;
}

.elegant-product-showcase .label_sale,
.elegant-product-showcase .label_stockout {
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.3);
}

.elegant-product-showcase .label_stockout {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
}

.elegant-product-showcase .action_links {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 3;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.elegant-product-showcase .single_product:hover .action_links {
    opacity: 1;
    transform: translateY(0);
}

.elegant-product-showcase .action_links ul {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.elegant-product-showcase .action_links li {
    margin: 0;
}

.elegant-product-showcase .action_links a {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0063d1;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
}

.elegant-product-showcase .action_links a:hover {
    background: #0063d1;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 99, 209, 0.4);
}

.elegant-product-showcase .product_content {
    padding: 25px 20px;
    position: relative;
    z-index: 2;
}

.elegant-product-showcase .product_name {
    margin-bottom: 15px;
}

.elegant-product-showcase .product_name a {
    color: #242424;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.elegant-product-showcase .product_name a:hover {
    color: #0063d1;
}

.elegant-product-showcase .price_box {
    margin-bottom: 20px;
}

.elegant-product-showcase .current_price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #0063d1;
}

.elegant-product-showcase .old_price {
    font-size: 1rem;
    color: #6c757d;
    text-decoration: line-through;
    margin-right: 8px;
}

.elegant-product-showcase .add_to_cart_overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 4;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
}

.elegant-product-showcase .single_product:hover .add_to_cart_overlay {
    opacity: 1;
    transform: translateY(0);
}

.elegant-product-showcase .add_to_cart_btn {
    width: 100%;
    padding: 12px 20px;
    background: linear-gradient(135deg, #0063d1, #354b65);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 99, 209, 0.3);
}

.elegant-product-showcase .add_to_cart_btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.elegant-product-showcase .add_to_cart_btn:hover::before {
    left: 100%;
}

.elegant-product-showcase .add_to_cart_btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 99, 209, 0.4);
}

.elegant-product-showcase .add_to_cart_btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .elegant-section-title h2 {
        font-size: 1.8rem;
    }
    
    .elegant-product-showcase .single_product {
        margin-bottom: 20px;
    }
    
    .elegant-product-showcase .product_thumb img {
        height: 200px;
    }
    
    .elegant-product-showcase .action_links {
        opacity: 1;
        transform: translateY(0);
    }
    
    .elegant-product-showcase .add_to_cart_overlay {
        opacity: 1;
        transform: translateY(0);
        position: relative;
        background: white;
        padding: 15px;
        margin-top: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
}
</style>

<script>
$(document).ready(function() {
    // Initialize carousel for each product showcase
    $('.elegant-carousel').each(function() {
        $(this).slick({
            infinite: true,
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
            dots: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
});
</script>



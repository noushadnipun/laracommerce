@php $productShowCase = \App\Helpers\WebsiteSettings::homeProductShowCase() @endphp
@if(!empty($productShowCase))
    @foreach(\App\Helpers\WebsiteSettings::homeProductShowCase() as $categoryID)
    <section class="elegant-product-showcase mb-46">
        <div class="section_title">
            <h2>{{\App\Models\ProductCategory::categoryName($categoryID)}}</h2>
        </div>
            
        <div class="product_slick product_slick_column5 elegant-carousel" id="product-carousel-{{ $categoryID }}">
            <?php 
                if(!empty($categoryID)){
                    $query = \App\Models\Product::productByCatId($categoryID)->where('visibility', '1')->limit('12')->get();
                    echo \App\Helpers\Frontend\ProductView::view($query); 
                }
            ?>
        </div> 
       
    </section>
    @endforeach
@endif


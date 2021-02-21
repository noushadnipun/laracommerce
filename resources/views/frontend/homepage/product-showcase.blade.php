@php $productShowCase = \App\Helpers\WebsiteSettings::homeProductShowCase() @endphp
@if(!empty($productShowCase))
    @foreach(\App\Helpers\WebsiteSettings::homeProductShowCase() as $categoryID)
    <section class="product_area mb-46">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>{{\App\Models\ProductCategory::categoryName($categoryID)}}</h2>
                    </div>
                </div>
            </div> 
            <div class="product_slick product_slick_column5">
                
                <?php 
                    if(!empty($categoryID)){
                        $query = \App\Models\Product::productByCatId($categoryID)->where('visibility', '1')->paginate('20');
                        echo \App\Helpers\Frontend\ProductView::view($query); 
                    }
                ?>
            </div> 
        </div>
    </section>
    @endforeach
@endif



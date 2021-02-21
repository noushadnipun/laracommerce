<div class="brand_area mb-70">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="brand_container owl-carousel">
                    @php 
                    $brands = \App\Models\ProductBrand::where('visibility', '1')
                                                    ->orderBy('created_at', 'DESC')
                                                    ->limit('20')->get() 
                   
                    @endphp
                    
                    <?php 
                     $i = 1;
                    foreach($brands as $key => $brand){?>
                        <?php 
                         if ($i%2 == 1){  
                            echo '<div class="brand_items">';
                        }
                        ?>
                        
                        <div class="single_brand">
                            <a href="{{route('frontend_single_product_brand', $brand->slug)}}"><img src="{{\App\Models\Media::fileLocation($brand->image)}}" alt=""></a>
                        </div>
                        
                        <?php 
                        if ($i%2 == 0){
                            echo '</div>';
                        }
                        $i++;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
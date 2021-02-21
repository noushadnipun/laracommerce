<!-- modal area start-->
 <div class="container">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            
            <div class="modal_tab">  
                <div class="tab-content product-details-large">
                    <?php foreach(\App\Helpers\WebsiteSettings::strToArr($product->product_image) as $key => $data) {?>
                    <div class="tab-pane fade {{$key == '0' ? 'show active' : ''}}" id="tab{{$data}}" role="tabpanel">
                        <div class="modal_tab_img">
                            <a href="javascript:void(0)"><img src="{{App\Models\Media::fileLocation($data)}}" alt=""></a>    
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="modal_tab_button">    
                    <ul class="nav product_navactive owl-carousel" role="tablist">
                        <?php foreach(\App\Helpers\WebsiteSettings::strToArr($product->product_image) as $key => $data) {?>
                        <li>
                            <a class="nav-link {{$key == '0' ? 'active' : ''}}" data-toggle="tab" href="#tab{{$data}}" role="tab" aria-controls="tab{{$data}}" aria-selected="false"><img src="{{App\Models\Media::fileLocation($data)}}" alt=""></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>    
            </div>  
            
        </div> 
        <div class="col-lg-7 col-md-7 col-sm-12">
            <div class="modal_right">
                <div class="modal_title mb-10">
                    <h2><?php echo $product->title?></h2> 
                </div>
                <div class="modal_price mb-10">
                     <span class="old_price"><?php echo !empty($product->sale_price) ? '৳'.$product->regular_price : ''?></span>  
                        <span class="current_price"><?php echo empty($product->sale_price) ? '৳'.$product->regular_price : '৳'.$product->sale_price ;?></span>    
                </div>
                <div class="modal_description mb-15">
                    <?php echo $product->short_description;?>  
                </div> 
                <div class="modal_add_to_cart">
                    <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                    @csrf
                    <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                        <div class="variants_selects">
                            @if(!empty($product->attribute))
                                @foreach($product->attribute as $key => $attr)
                                    <div class="variants_size">
                                        <h2>{{$key}}</h2>
                                        <select class="select_option nice-select" name="attribute[{{$key}}][]">
                                            @foreach($attr as $val)
                                            <option>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            @endif
                            <div class="modal_add_to_cart">
                                <input min="1" max="100" value="1" type="number" name="quantity">
                                <button type="submit">add to cart</button>
                            </div>   
                        </div>
                    </form>
                </div>
                <div class="modal_social">
                    <h2>Share this product</h2>
                    <ul>
                        <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                        <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    </ul>    
                </div>      
            </div>    
        </div>    
    </div>     
</div>
             
<!-- modal area end-->



<script>
    $('.owl-carousel').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        dots:true,
        autoplay:true,
        autoplayHoverPause:true,
        items:4,
        responsive:{
          0:{
            items:1
          },
            480:{
        items:2
          },
            768 :{
        items:4  
          }
        }
    })
</script>

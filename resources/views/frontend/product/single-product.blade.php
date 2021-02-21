@extends('frontend.layouts.master')

@section('page-content')

 <!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="container">   
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="{{url('/')}}"><i class="fa fa-home"></i></a></li>
                        <li>{{$product->title}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>         
</div>
<!--breadcrumbs area end-->




 <!--product details start-->
<div class="product_details mt-60 mb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-details-tab">
                    <div id="img-1" class="zoomWrapper single-zoom">
                        <a href="javascript:void(0)">
                            <img id="zoom1" src="{{App\Models\Media::fileLocation($product->featured_image)}}" data-zoom-image="{{App\Models\Media::fileLocation($product->featured_image)}}" alt="">
                        </a>
                    </div>
                    <div class="single-zoom-thumb">
                        <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                            @foreach(\App\Helpers\WebsiteSettings::strToArr($product->product_image) as $key => $data)
                            <li>
                                <a href="javascript:void()" class="elevatezoom-gallery active" data-update="" data-image="{{App\Models\Media::fileLocation($data)}}" data-zoom-image="{{App\Models\Media::fileLocation($data)}}">
                                    <img src="{{App\Models\Media::fileLocation($data)}}" alt="">
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product_d_right">
                    <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                    @csrf
                        <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                        <h1>{{$product->title}}</h1>
                        {{-- <div class="product_nav">
                            <ul>
                                <li class="prev"><a href="#"><i class="fa fa-angle-left"></i></a></li>
                                <li class="next"><a href="#"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div> --}}
                        <div class="price_box">
                            <span class="old_price"><?php echo !empty($product->sale_price) ? '৳'.$product->regular_price : ''?></span>  
                            <span class="current_price"><?php echo empty($product->sale_price) ? '৳'.$product->regular_price : '৳'.$product->sale_price ;?></span> 
                            
                        </div>
                        <div class="product_desc">
                            <?php echo $product->short_description;?>
                        </div>
                        @if(!empty($product->attribute))
                        <div class="product_variant color mb-2">
                            <h3>Available Options</h3>
                        </div>
                        <div class="variants_selects">
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
                        </div>
                        @endif
                            
                        
                        <div class="product_variant quantity d-block">
                            <label>quantity</label>
                            <input min="1" max="100" value="1" type="number" name="quantity">
                            <button class="button" type="submit">add to cart</button>  
                        </div>
                        <div class="product_d_action d-none">
                            <ul>
                                <li><a href="#" title="Add to wishlist">+ Add to Wishlist</a></li>
                                <li><a href="#" title="Add to wishlist">+ Compare</a></li>
                            </ul>
                        </div>
                        <div class="product_meta">
                            @if(!empty($product->category_id))
                            <span>Category: 
                                @foreach(App\Helpers\WebsiteSettings::strToArr($product->category_id) as $cat)
                                <a href="{{route('frontend_single_product_category', \App\Models\ProductCategory::categorySlug($cat))}}">
                                    {{\App\Models\ProductCategory::categoryName($cat)}}
                                </a>
                                @endforeach
                            </span>
                            @endif
                        </div>
                        
                    </form>
                    <?php /*
                    <div class="priduct_social">
                        <ul>
                            <li><a class="facebook" href="#" title="facebook"><i class="fa fa-facebook"></i> Like</a></li>           
                            <li><a class="twitter" href="#" title="twitter"><i class="fa fa-twitter"></i> tweet</a></li>           
                            <li><a class="pinterest" href="#" title="pinterest"><i class="fa fa-pinterest"></i> save</a></li>           
                            <li><a class="google-plus" href="#" title="google +"><i class="fa fa-google-plus"></i> share</a></li>        
                            <li><a class="linkedin" href="#" title="linkedin"><i class="fa fa-linkedin"></i> linked</a></li>        
                        </ul>      
                    </div>
                    */ ?>

                </div>
            </div>
        </div>
    </div>    
</div>
<!--product details end-->
    
    <!--product info start-->
    <div class="product_d_info mb-60">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="product_d_inner">   
                        <div class="product_info_button">    
                            <ul class="nav" role="tablist">
                                <li >
                                    <a class="active" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Description</a>
                                </li>
                                @if(!empty($product->specification))
                                <li>
                                     <a data-toggle="tab" href="#sheet" role="tab" aria-controls="sheet" aria-selected="false">Specification</a>
                                </li>
                                @endif
                                {{-- <li>
                                   <a data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews (1)</a>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" >
                                <div class="product_info_content">
                                    <?php echo $product->description;?>
                                </div>    
                            </div>
                            <div class="tab-pane fade" id="sheet" role="tabpanel" >
                                <?php echo $product->specification;?>
                            </div>

                            <div class="tab-pane fade" id="reviews" role="tabpanel" >
                                <div class="reviews_wrapper">
                                    <h2>1 review for Donec eu furniture</h2>
                                    <div class="reviews_comment_box">
                                        <div class="comment_thmb">
                                            <img src="assets/img/blog/comment2.jpg" alt="">
                                        </div>
                                        <div class="comment_text">
                                            <div class="reviews_meta">
                                                <div class="star_rating">
                                                    <ul>
                                                        <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                        <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                        <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                        <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                        <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                    </ul>   
                                                </div>
                                                <p><strong>admin </strong>- September 12, 2018</p>
                                                <span>roadthemes</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="comment_title">
                                        <h2>Add a review </h2>
                                        <p>Your email address will not be published.  Required fields are marked </p>
                                    </div>
                                    <div class="product_ratting mb-10">
                                       <h3>Your rating</h3>
                                        <ul>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="product_review_form">
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="review_comment">Your review </label>
                                                    <textarea name="comment" id="review_comment" ></textarea>
                                                </div> 
                                                <div class="col-lg-6 col-md-6">
                                                    <label for="author">Name</label>
                                                    <input id="author"  type="text">

                                                </div> 
                                                <div class="col-lg-6 col-md-6">
                                                    <label for="email">Email </label>
                                                    <input id="email"  type="text">
                                                </div>  
                                            </div>
                                            <button type="submit">Submit</button>
                                         </form>   
                                    </div> 
                                </div>     
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        </div>    
    </div>  
    <!--product info end-->
    
    <!--product area start-->
    <section class="product_area related_products">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>Related Products</h2>
                    </div>
                </div>
            </div> 
            <div class="product_carousel product_column5 owl-carousel">
                <?php 
                    $relatedProduct = \App\Models\Product::productByCatIdHasComma($product->category_id)->whereNull('sale_price')->paginate('30');
                    echo \App\Helpers\Frontend\ProductView::view($relatedProduct);
                    //echo $product->category_id; 
                ?>
            </div>   
        </div>
    </section>
    <!--product area end-->
    
     <!--product area start-->
    <section class="product_area upsell_products">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2>Upsell Products	</h2>
                    </div>
                </div>
            </div> 
            <div class="product_carousel product_column5 owl-carousel">
                <?php 
                    $upSellProduct = \App\Models\Product::productByCatIdHasComma($product->category_id)->whereNotNull('sale_price')->paginate('30');
                    echo \App\Helpers\Frontend\ProductView::view($upSellProduct); 
                ?>
            </div>   
        </div>
    </section>
    <!--product area end-->



@endsection
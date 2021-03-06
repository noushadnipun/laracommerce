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
                        <li>{{$categoryName}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>         
</div>
<!--breadcrumbs area end-->


    <!--shop  area start-->
    <div class="shop_area mt-60 mb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <!--shop wrapper start-->
                    <!--shop toolbar start-->
                    <div class="shop_toolbar_wrapper">
                        <div class="shop_toolbar_btn">

                            <button data-role="grid_3" type="button" class="active btn-grid-3" data-toggle="tooltip" title="3"></button>

                            <button data-role="grid_4" type="button"  class=" btn-grid-4" data-toggle="tooltip" title="4"></button>

                            <button data-role="grid_list" type="button"  class="btn-list" data-toggle="tooltip" title="List"></button>
                        </div>
                        <div class=" niceselect_option">
                            <form class="select_option" action="#" method="post">
                                <select name="orderby" id="short" onchange='this.form.submit()'>
                                    <option selected  value="2">Sort by popularity</option>
                                    <option value="3">Sort by newness</option>
                                    <option value="4">Sort by price: low to high</option>
                                    <option value="5">Sort by price: high to low</option>
                                    <option value="6">Product Name: Z</option>
                                </select>
                                <noscript><input type="submit" value="Submit"></noscript>
                            </form>
                        </div>
                        <div class="page_amount">
                            {{-- <p>Showing 1–9 of 21 results</p> --}}
                        </div>
                    </div>
                     <!--shop toolbar end-->

                     <div class="row shop_wrapper">
                        @foreach($getProduct as $product)
                        <div class="col-lg-4 col-md-4 col-12 ">
                             <article class="single_product">
                                <figure>
                                    <div class="product_thumb">
                                        <a class="primary_img" href="<?php echo route('frontend_single_product', $product->slug);?>"><img src="<?php echo \App\Models\Media::fileLocation($product->featured_image)?>" alt=""></a>
                                        <div class="label_product">
                                        <?php echo !empty($product->sale_price) ? '<span class="label_sale">sale</span>' : '' ?>
                                        </div>
                                        <div class="action_links">
                                            <ul>
                                                <li class="quick_button"><a id="<?php echo $product->id;?>" class="modalQuickView" href="javascript:void()" xdata-toggle="modal" xdata-target="#modal_box" title="quick view"> <span class="ion-ios-search-strong"></span></a></li>
                                            </ul>
                                        </div>
                                        <div class="add_to_cart">
                                            <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                                                <button class="add_to_cart_btn" type="submit" title="add to cart">Add to cart</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="product_content grid_content">
                                        <div class="price_box">
                                                <span class="old_price"><?php echo !empty($product->sale_price) ? '৳'.$product->regular_price : ''?></span>  
                                                <span class="current_price"><?php echo empty($product->sale_price) ? '৳'.$product->regular_price : '৳'.$product->sale_price ;?></span> 
                                        </div>
                                        <div class="product_ratings">
                                            
                                        </div>
                                        <h3 class="product_name grid_name"><a href="product-details.html"><?php echo $product->title;?></a></h3>
                                    </div>

                                    <div class="product_content list_content">
                                        <div class="left_caption">
                                           <div class="price_box">
                                                    <span class="old_price"><?php echo !empty($product->sale_price) ? '৳'.$product->regular_price : ''?></span>  
                                                    <span class="current_price"><?php echo empty($product->sale_price) ? '৳'.$product->regular_price : '৳'.$product->sale_price ;?></span> 
                                            </div>
                                            <h3 class="product_name"><a href="product-details.html"><?php echo $product->title;?></a></h3>
                                            <div class="product_ratings d-none">
                                                <ul>
                                                    <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                                                    <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                                                    <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                                                    <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                                                    <li><a href="#"><i class="ion-android-star-outline"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product_desc">
                                                <?php echo $product->short_description;?>
                                            </div>
                                        </div>
                                        <div class="right_caption">
                                            <div class="add_to_cart">
                                                <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                                                    <button class="" type="submit" title="add to cart">Add to cart</button>
                                                </form>
                                            </div>
                                            <div class="action_links">
                                                <ul>
                                                    <li class="quick_button"><a id="<?php echo $product->id;?>" class="modalQuickView" href="javascript:void()" xdata-toggle="modal" xdata-target="#modal_box" title="quick view"> Quick View</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </figure>
                            </article>




                        </div>
                        @endforeach
                    </div>

                    <div class="shop_toolbar t_bottom">
                        <div class="pagination">
                            {{$getProduct->links()}}
                        </div>
                    </div>
                    <!--shop toolbar end-->
                    <!--shop wrapper end-->
                </div>
                <div class="col-lg-3 col-md-12">
                   <!--sidebar widget start-->
                    @include('frontend.product.product-sidebar')
                    <!--sidebar widget end-->
                </div>
            </div>
        </div>
    </div>
    <!--shop  area end-->


@endsection
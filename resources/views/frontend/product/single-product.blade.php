@extends('frontend.layouts.master')

@section('page-content')

 <!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="container-fluid">   
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-gallery-container">
                    <!-- Main Product Image -->
                    <div class="main-product-image">
                        <div class="image-container">
                            <img id="main-product-img" src="{{$product->getFeaturedImageUrl()}}" alt="{{$product->title}}" onerror="this.src='{{asset('public/frontend/images/no-images.svg')}}'">
                            <div class="image-overlay">
                                <button class="zoom-btn" id="zoom-btn">
                                    <i class="fa fa-search-plus"></i>
                                </button>
                    </div>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <div class="thumbnail-gallery">
                        <div class="gallery-nav">
                            <button class="gallery-prev" id="gallery-prev">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="gallery-next" id="gallery-next">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="thumbnails-container" id="thumbnails-container">
                            @foreach($product->getAllImages() as $key => $image)
                            <div class="thumbnail-item {{$key == 0 ? 'active' : ''}}" data-image="{{$image['url']}}">
                                <img src="{{$image['url']}}" alt="{{$product->title}}" onerror="this.src='{{asset('public/frontend/images/no-images.svg')}}'">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product_d_right elegant-product-info">
                    <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                    @csrf
                        <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                        
                        <!-- Product Title Section -->
                        <div class="product-title-section">
                            <h1 class="product-title">{{$product->title}}</h1>
                            <div class="product-badges">
                                @if($product->featured)
                                    <span class="badge badge-featured">
                                        <i class="fa fa-star"></i> Featured
                                    </span>
                                @endif
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="badge badge-sale">
                                        <i class="fa fa-tag"></i> On Sale
                                    </span>
                                @endif
                                @if($product->current_stock <= 5 && $product->current_stock > 0)
                                    <span class="badge badge-limited">
                                        <i class="fa fa-exclamation-triangle"></i> Limited Stock
                                    </span>
                            @endif
                            </div>
                        </div>
                        
                        <!-- Price Section -->
                        <div class="price-section">
                            <div class="price-main">
                                @if(!empty($product->sale_price) && $product->sale_price < $product->regular_price)
                                    <span class="old-price">৳{{number_format($product->regular_price / 100, 2)}}</span>
                                    <span class="current-price">৳{{number_format($product->sale_price / 100, 2)}}</span>
                                    <span class="discount-badge">-{{round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)}}% OFF</span>
                                @else
                                    <span class="current-price">৳{{number_format($product->regular_price / 100, 2)}}</span>
                                @endif
                                </div>
                            <div class="price-details">
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="savings">You Save: ৳{{number_format(($product->regular_price - $product->sale_price) / 100, 2)}}</span>
                            @endif
                            </div>
                        </div>
                        
                        <!-- Product Information Cards -->
                        <div class="product-info-cards">
                            <!-- Stock & Availability -->
                            <div class="info-card stock-card">
                                <div class="info-icon">
                                    <i class="fa fa-warehouse"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Availability</div>
                                    <div class="info-value">
                                @if($product->current_stock > 0)
                                            <span class="stock-in">In Stock</span>
                                            <span class="stock-count">({{$product->current_stock}} available)</span>
                                @else
                                            <span class="stock-out">Out of Stock</span>
                                @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Code -->
                            @if($product->code)
                            <div class="info-card sku-card">
                                <div class="info-icon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Product Code</div>
                                    <div class="info-value">{{$product->code}}</div>
                                </div>
                            </div>
                            @endif

                            <!-- Brand Information -->
                            @if($product->brand)
                            <div class="info-card brand-card">
                                <div class="info-icon">
                                    <i class="fa fa-trademark"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Brand</div>
                                    <div class="info-value">
                                        <a href="{{route('frontend_single_product_brand', $product->brand->slug)}}" class="brand-link">
                                        {{$product->brand->name}}
                                    </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Shipping Information -->
                            <div class="info-card shipping-card">
                                <div class="info-icon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Shipping</div>
                                    <div class="info-value">
                                        @if($product->shipping_type == 'free')
                                            <span class="shipping-free">Free Shipping</span>
                                        @elseif($product->shipping_cost)
                                            <span class="shipping-paid">৳{{number_format($product->shipping_cost / 100, 2)}}</span>
                                        @else
                                            <span class="shipping-standard">Standard Shipping</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Return Policy -->
                            @if($product->refundable)
                            <div class="info-card return-card">
                                <div class="info-icon">
                                    <i class="fa fa-undo"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Returns</div>
                                    <div class="info-value">
                                        <span class="return-available">30 Days Return</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Warranty Information -->
                            @if($product->warranty)
                            <div class="info-card warranty-card">
                                <div class="info-icon">
                                    <i class="fa fa-shield"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Warranty</div>
                                    <div class="info-value">{{$product->warranty}}</div>
                                </div>
                                </div>
                            @endif
                        </div>
                        <div class="product_desc">
                            <?php echo $product->short_description;?>
                        </div>
                        
        <!-- Product Statistics -->
        <div class="product_stats mb-3">
            <div class="row text-center">
                <div class="col-4">
                    <div class="stat-item">
                        <i class="fa fa-eye text-info"></i>
                        <div class="stat-number">{{$product->statistics ? $product->statistics->views : 0}}</div>
                        <div class="stat-label">Views</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item">
                        <i class="fa fa-shopping-cart text-success"></i>
                        <div class="stat-number">{{$product->statistics ? $product->statistics->total_sales : 0}}</div>
                        <div class="stat-label">Sold</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item">
                        <i class="fa fa-star text-warning"></i>
                        <div class="stat-number" id="average-rating">{{number_format($product->getAverageRating(), 1)}}/5</div>
                        <div class="stat-label">Rating ({{$product->getReviewCount()}})</div>
                    </div>
                </div>
            </div>
        </div>
					@php
						// Get product-specific allowed attributes
						$productAttributes = is_array($product->attribute) ? $product->attribute : (json_decode($product->attribute, true) ?: []);
						$attrNames = array_keys($productAttributes);
						$attributes = collect();
						if (!empty($attrNames)) {
							$attributes = \App\Models\ProductAttribute::with('activeValues')
								->whereIn('name', $attrNames)
								->where('is_active', true)
								->orderBy('sort_order')
								->get();
						}
					@endphp
					@if($attributes->count() > 0)
					<div class="product_variant color mb-2">
						<h3>Available Options</h3>
					</div>
					<div class="variants_selects">
						@foreach($attributes as $attr)
						@php
							$allowed = (array)($productAttributes[$attr->name] ?? []);
							$allowed = array_map('strval', $allowed);
							$values = $attr->activeValues->filter(function($v) use ($allowed){ return in_array((string)$v->value, $allowed, true); });
							if ($values->isEmpty()) continue;
						@endphp
						<div class="variants_size mb-2">
							<h2>{{ $attr->name }}</h2>

							@if($attr->type === 'color')
								<div class="color-swatch-container">
									@foreach($values as $val)
									<label class="color-swatch-option" title="{{ $val->value }}">
										<input type="radio" name="attribute[{{ $attr->name }}]" value="{{ $val->value }}" required>
										<span class="color-swatch" style="background-color: {{ $val->color_code ?? '#ddd' }};"></span>
										<span class="color-label">{{ $val->value }}</span>
									</label>
									@endforeach
								</div>
							@elseif($attr->type === 'image')
								<div class="image-grid-container">
									@foreach($values as $val)
									<label class="image-grid-option" title="{{ $val->value }}">
										<input type="radio" name="attribute[{{ $attr->name }}]" value="{{ $val->value }}" required>
										@if($val->image)
											<img src="{{ $val->image }}" alt="{{ $val->value }}" class="image-grid-img">
										@else
											<div class="image-grid-placeholder">{{ $val->value }}</div>
										@endif
									</label>
									@endforeach
								</div>
							@elseif($attr->display_type === 'radio')
								<div class="radio-group">
									@foreach($values as $val)
									<label class="form-check-inline">
										<input type="radio" name="attribute[{{ $attr->name }}]" value="{{ $val->value }}" required>
										{{ $val->value }}
									</label>
									@endforeach
								</div>
							@else
								<select class="select_option nice-select" name="attribute[{{ $attr->name }}]" {{ $attr->is_required ? 'required' : '' }}>
									<option value="" disabled selected>Select {{ $attr->name }}</option>
									@foreach($values as $val)
										<option value="{{ $val->value }}">{{ $val->value }}</option>
									@endforeach
								</select>
							@endif
						</div>
						@endforeach
					</div>
					@endif
                            
                        
                        @if($product->current_stock > 0)
                        <div class="product_variant quantity d-block">
                            <label>quantity</label>
                            <input min="1" max="100" value="1" type="number" name="quantity">
                            <button class="button" type="submit">add to cart</button>  
                        </div>
                        @else
                        <div class="product_variant quantity d-block">
                            <label>quantity</label>
                            <input min="1" max="100" value="1" type="number" name="quantity" disabled>
                            <button class="button" type="button" disabled>sold</button>
                        </div>
                        @endif
                        <div class="product_d_action">
                            <ul>
                                <li><a href="javascript:void(0)" class="wishlist-btn" data-product-id="{{$product->id}}" data-route-toggle="{{route('frontend_wishlist_toggle')}}" title="Add to wishlist">
                                    <i class="fa fa-heart"></i> Add to Wishlist
                                </a></li>
                                <li><a href="javascript:void(0)" class="compare-btn" data-product-id="{{$product->id}}" title="Add to compare">
                                    <i class="fa fa-balance-scale"></i> Compare
                                </a></li>
                                <li><a href="#" class="share-btn" title="Share product">
                                    <i class="fa fa-share-alt"></i> Share
                                </a></li>
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
                            
                            @if($product->shipping_type)
                            <span class="shipping_info">
                                <i class="fa fa-truck"></i> 
                                @if($product->shipping_type == 'free')
                                    Free Shipping
                                @else
                                    Shipping: ৳{{number_format($product->shipping_cost / 100, 2)}}
                                @endif
                            </span>
                            @endif
                            
                            @if($product->refundable)
                            <span class="refund_info">
                                <i class="fa fa-undo"></i> Refundable
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
        <div class="container-fluid">   
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
                                @if($product->sizeGuides()->count() > 0)
                                <li>
                                     <a data-toggle="tab" href="#size-guide" role="tab" aria-controls="size-guide" aria-selected="false">Size Guide</a>
                                </li>
                                @endif
                                <li>
                                    <a data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                                        Reviews (<span id="review-count">{{$product->getReviewCount()}}</span>)
                                    </a>
                                </li>
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

                            @if($product->sizeGuides()->count() > 0)
                            <div class="tab-pane fade" id="size-guide" role="tabpanel">
                                <div class="size_guide_wrapper">
                                    @foreach($product->sizeGuides as $sizeGuide)
                                    <div class="size_guide_item mb-4">
                                        <h4>{{$sizeGuide->title}}</h4>
                                        @if($sizeGuide->description)
                                            <p class="text-muted">{{$sizeGuide->description}}</p>
                                        @endif
                                        
                                        @if($sizeGuide->size_chart)
                                            <div class="size_chart_table">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Size</th>
                                                            @if(isset($sizeGuide->size_chart['measurements']))
                                                                @foreach(array_keys($sizeGuide->size_chart['measurements']) as $measurement)
                                                                    <th>{{ucfirst($measurement)}}</th>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($sizeGuide->size_chart['sizes']) && isset($sizeGuide->size_chart['measurements']))
                                                            @for($i = 0; $i < count($sizeGuide->size_chart['sizes']); $i++)
                                                                <tr>
                                                                    <td><strong>{{$sizeGuide->size_chart['sizes'][$i]}}</strong></td>
                                                                    @foreach($sizeGuide->size_chart['measurements'] as $measurement => $values)
                                                                        <td>{{$values[$i] ?? 'N/A'}}</td>
                                                                    @endforeach
                                                                </tr>
                                                            @endfor
                                                        @endif
                                                    </tbody>
                                                </table>
                                                @if(isset($sizeGuide->size_chart['units']))
                                                    <small class="text-muted">All measurements in {{$sizeGuide->size_chart['units']}}</small>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($sizeGuide->measurement_guide)
                                            <div class="measurement_guide mt-3">
                                                <h5>How to Measure</h5>
                                                <div class="measurement_content">
                                                    {!! nl2br(e($sizeGuide->measurement_guide)) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="reviews_wrapper">
                                    <!-- Review Statistics -->
                                    <div class="review_stats mb-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="overall_rating text-center">
                                                    <div class="rating_display">
                                                        <span class="rating_number">{{number_format($product->getAverageRating(), 1)}}</span>
                                                        <div class="star_rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= floor($product->getAverageRating()))
                                                                    <i class="fa fa-star text-warning"></i>
                                                                @elseif($i - 0.5 <= $product->getAverageRating())
                                                                    <i class="fa fa-star-half-o text-warning"></i>
                                                                @else
                                                                    <i class="fa fa-star-o text-muted"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <p class="rating_text">Based on {{$product->getReviewCount()}} reviews</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="rating_breakdown">
                                                    @php
                                                        $ratingDistribution = \App\Models\Product\ProductReview::getRatingDistribution($product->id);
                                                        $totalReviews = $product->getReviewCount();
                                                    @endphp
                                                    @for($rating = 5; $rating >= 1; $rating--)
                                                        @php
                                                            $count = $ratingDistribution[$rating] ?? 0;
                                                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                                        @endphp
                                                        <div class="rating_bar">
                                                            <div class="rating_label">
                                                                <span>{{$rating}} <i class="fa fa-star text-warning"></i></span>
                                                            </div>
                                                            <div class="rating_progress">
                                                                <div class="progress">
                                                                    <div class="progress-bar" style="width: {{$percentage}}%"></div>
                                                                </div>
                                                            </div>
                                                            <div class="rating_count">{{$count}}</div>
                                                        </div>
                                                    @endfor
                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    <!-- Reviews List -->
                                    <div class="reviews_list" id="reviews-list">
                                        <h3>Customer Reviews</h3>
                                        <div id="reviews-container">
                                            @foreach($product->approvedReviews()->limit(5)->get() as $review)
                                            <div class="review_item">
                                                <div class="review_header">
                                                    <div class="reviewer_info">
                                                        <h4>{{$review->name}}</h4>
                                                        <div class="review_meta">
                                                            <div class="star_rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                        <i class="fa fa-star text-warning"></i>
                                                                    @else
                                                                        <i class="fa fa-star-o text-muted"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <span class="review_date">{{$review->created_at->format('M d, Y')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review_content">
                                                    @if($review->comment)
                                                        <p>{{$review->comment}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            @if($product->getReviewCount() == 0)
                                                <div class="no_reviews text-center py-4">
                                                    <i class="fa fa-comment-o" style="font-size: 48px; color: #ddd;"></i>
                                                    <h4>No reviews yet</h4>
                                                    <p>Be the first to review this product!</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($product->getReviewCount() > 5)
                                            <div class="text-center mt-3">
                                                <button class="btn btn-outline-primary" id="load-more-reviews">Load More Reviews</button>
                                    </div>
                                        @endif
                                    </div>

                                    <!-- Add Review Form -->
                                    <div class="add_review_section mt-5">
                                        <div class="review_form_title">
                                            <h3>Write a Review</h3>
                                            <p>Your email address will not be published. Required fields are marked *</p>
                                    </div>
                                        
                                        <div id="review-form-container">
                                            @if(Auth::check())
                                                @if(!\App\Models\Product\ProductReview::hasUserReviewed(Auth::id(), $product->id))
                                                    <form id="review-form">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{$product->id}}">
                                                        
                                            <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="review_name">Name *</label>
                                                                    <input type="text" class="form-control" id="review_name" name="name" 
                                                                           value="{{Auth::user()->name}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="review_email">Email *</label>
                                                                    <input type="email" class="form-control" id="review_email" name="email" 
                                                                           value="{{Auth::user()->email}}" required>
                                                                </div>
                                                            </div>
                                                </div> 
                                                        
                                                        <div class="form-group">
                                                            <label>Your Rating *</label>
                                                            <div class="rating_input">
                                                                <div class="star_rating_input">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="fa fa-star-o rating-star" data-rating="{{$i}}"></i>
                                                                    @endfor
                                                </div> 
                                                                <input type="hidden" name="rating" id="rating_value" required>
                                                                <span class="rating_text">Click to rate</span>
                                                </div>  

                                            </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="review_comment">Your Review</label>
                                                            <textarea class="form-control" id="review_comment" name="comment" 
                                                                      rows="4" placeholder="Tell us about your experience with this product..."></textarea>
                                                        </div>
                                                        
                                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                         </form>   
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fa fa-info-circle"></i> You have already reviewed this product.
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fa fa-lock"></i> Please <a href="{{route('frontend_customer_login')}}">login</a> to write a review.
                                                </div>
                                            @endif
                                        </div>
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
    
    <!-- Regular Price Products Section -->
    <section class="product_area regular_products">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2><i class="fa fa-tag"></i> Regular Price Products</h2>
                        <p class="section-subtitle">Other products in this category at regular price</p>
                    </div>
                </div>
            </div> 
            <div class="product_carousel product_column5 owl-carousel">
                <?php 
                    if($product->category_id) {
                    $regularProducts = \App\Models\Product::productByCatIdHasComma($product->category_id)
                        ->whereNull('sale_price')
                        ->where('id', '!=', $product->id)
                        ->where('visibility', '1')
                        ->limit(10)
                        ->get();
                    echo \App\Helpers\Frontend\ProductView::view($regularProducts);
                    }
                ?>
            </div>   
        </div>
    </section>
    <!-- Regular Price Products End -->
    
    <!-- Sale Products Section -->
    <section class="product_area sale_products">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2><i class="fa fa-fire"></i> On Sale Products</h2>
                        <p class="section-subtitle">Special offers and discounted products in this category</p>
                    </div>
                </div>
            </div> 
            <div class="product_carousel product_column5 owl-carousel">
                <?php 
                    if($product->category_id) {
                    $saleProducts = \App\Models\Product::productByCatIdHasComma($product->category_id)
                        ->whereNotNull('sale_price')
                        ->where('id', '!=', $product->id)
                        ->where('visibility', '1')
                        ->limit(10)
                        ->get();
                    echo \App\Helpers\Frontend\ProductView::view($saleProducts); 
                    }
                ?>
            </div>   
        </div>
    </section>
    <!-- Sale Products End -->

    <!-- Related Products Section -->
    <section class="product_area related_products">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section_title">
                        <h2><i class="fa fa-th-large"></i> You May Also Like</h2>
                        <p class="section-subtitle">Similar products you might be interested in</p>
                    </div>
                </div>
            </div> 
            <div class="product_carousel product_column4 owl-carousel">
                <?php 
                    if($product->category_id) {
                        $relatedProducts = \App\Models\Product::productByCatIdHasComma($product->category_id)
                            ->where('id', '!=', $product->id)
                            ->where('visibility', '1')
                            ->inRandomOrder()
                            ->limit(8)
                            ->get();
                        echo \App\Helpers\Frontend\ProductView::view($relatedProducts); 
                    }
                ?>
            </div>   
        </div>
    </section>
    <!-- Related Products End -->

    <!-- Product Modal -->
    <?php echo \App\Helpers\Frontend\ProductView::productModal(); ?>

    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageZoomModal" tabindex="-1" role="dialog" aria-labelledby="imageZoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content elegant-modal">
                <div class="modal-header elegant-header">
                    <div class="modal-title-section">
                        <h5 class="modal-title" id="imageZoomModalLabel">{{$product->title}}</h5>
                        <div class="image-counter">
                            <span id="current-image-number">1</span> / <span id="total-images">{{count($product->getAllImages())}}</span>
                        </div>
                    </div>
                    <button type="button" class="close elegant-close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body elegant-body">
                    <div class="zoom-gallery-container">
                        <!-- Navigation Arrows -->
                        <button class="modal-nav-btn modal-prev-btn" id="modal-prev">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="modal-nav-btn modal-next-btn" id="modal-next">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                        
                        <!-- Main Image Container -->
                        <div class="zoom-image-container">
                            <img id="zoom-image" src="{{$product->getFeaturedImageUrl()}}" alt="{{$product->title}}" class="zoom-main-image">
                            <div class="image-loading" id="image-loading">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        
                        <!-- Thumbnail Strip -->
                        <div class="modal-thumbnails">
                            <div class="thumbnails-strip" id="modal-thumbnails">
                                @foreach($product->getAllImages() as $key => $image)
                                <div class="modal-thumbnail {{$key == 0 ? 'active' : ''}}" data-image="{{$image['url']}}" data-index="{{$key}}">
                                    <img src="{{$image['url']}}" alt="{{$product->title}}" onerror="this.src='{{asset('public/frontend/images/no-images.svg')}}'">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Zoom Controls -->
                        <div class="zoom-controls">
                            <button class="zoom-control-btn" id="zoom-in">
                                <i class="fa fa-search-plus"></i>
                            </button>
                            <button class="zoom-control-btn" id="zoom-out">
                                <i class="fa fa-search-minus"></i>
                            </button>
                            <button class="zoom-control-btn" id="zoom-reset">
                                <i class="fa fa-expand"></i>
                            </button>
                            <button class="zoom-control-btn" id="fullscreen-toggle">
                                <i class="fa fa-arrows-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Sharing Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share This Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="social-sharing">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(request()->url())}}" target="_blank" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-facebook"></i> Share on Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{urlencode(request()->url())}}&text={{urlencode($product->title)}}" target="_blank" class="btn btn-info btn-block mb-2">
                            <i class="fa fa-twitter"></i> Share on Twitter
                        </a>
                        <a href="https://wa.me/?text={{urlencode($product->title . ' - ' . request()->url())}}" target="_blank" class="btn btn-success btn-block mb-2">
                            <i class="fa fa-whatsapp"></i> Share on WhatsApp
                        </a>
                        <a href="https://pinterest.com/pin/create/button/?url={{urlencode(request()->url())}}&media={{urlencode($product->getFeaturedImageUrl())}}&description={{urlencode($product->title)}}" target="_blank" class="btn btn-danger btn-block mb-2">
                            <i class="fa fa-pinterest"></i> Pin on Pinterest
                        </a>
                        <a href="mailto:?subject={{urlencode($product->title)}}&body={{urlencode('Check out this product: ' . request()->url())}}" class="btn btn-secondary btn-block mb-2">
                            <i class="fa fa-envelope"></i> Share via Email
                        </a>
                    </div>
                    <div class="mt-3">
                        <label>Copy Link:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="productUrl" value="{{request()->url()}}" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="copyUrlBtn">Copy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        /* Product Gallery Styles */
        .product-gallery-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .main-product-image {
            position: relative;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }
        
        .image-container {
            position: relative;
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        
        .image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .main-product-image:hover .image-overlay {
            opacity: 1;
        }
        
        .zoom-btn {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .zoom-btn:hover {
            background: #fff;
            transform: scale(1.1);
        }
        
        /* Thumbnail Gallery */
        .thumbnail-gallery {
            position: relative;
        }
        
        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
        
        .gallery-prev {
            left: -15px;
            position: absolute;
        }
        
        .gallery-next {
            right: -15px;
            position: absolute;
        }
        
        .gallery-prev, .gallery-next {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .gallery-prev:hover, .gallery-next:hover {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        
        .thumbnails-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
            scrollbar-width: thin;
        }
        
        .thumbnails-container::-webkit-scrollbar {
            height: 4px;
        }
        
        .thumbnails-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }
        
        .thumbnails-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        .thumbnail-item {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .thumbnail-item:hover {
            border-color: #007bff;
            transform: scale(1.05);
        }
        
        .thumbnail-item.active {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
        }
        
        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Elegant Zoom Modal */
        .elegant-modal {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            background: #000;
        }
        
        .elegant-header {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            border-bottom: 1px solid #333;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title-section {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .elegant-header .modal-title {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        
        .image-counter {
            color: #ccc;
            font-size: 14px;
            font-weight: 500;
        }
        
        .elegant-close {
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            transition: all 0.3s ease;
            opacity: 0.9;
            cursor: pointer;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .elegant-close:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.4);
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        .elegant-close:active {
            transform: scale(0.95);
        }
        
        .elegant-close i {
            font-weight: 600;
        }
        
        .elegant-body {
            background: #000;
            padding: 0;
            position: relative;
        }
        
        .zoom-gallery-container {
            position: relative;
            height: 70vh;
            display: flex;
            flex-direction: column;
        }
        
        .zoom-image-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: #000;
            overflow: hidden;
        }
        
        .zoom-main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: all 0.3s ease;
            cursor: grab;
        }
        
        .zoom-main-image:active {
            cursor: grabbing;
        }
        
        .image-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 24px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-loading.show {
            opacity: 1;
        }
        
        /* Modal Navigation Buttons */
        .modal-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            backdrop-filter: blur(10px);
        }
        
        .modal-nav-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-50%) scale(1.1);
        }
        
        .modal-prev-btn {
            left: 20px;
        }
        
        .modal-next-btn {
            right: 20px;
        }
        
        /* Modal Thumbnails */
        .modal-thumbnails {
            background: rgba(0,0,0,0.8);
            padding: 15px 20px;
            border-top: 1px solid #333;
        }
        
        .thumbnails-strip {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 5px 0;
            justify-content: center;
        }
        
        .thumbnails-strip::-webkit-scrollbar {
            height: 4px;
        }
        
        .thumbnails-strip::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
        }
        
        .thumbnails-strip::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
        }
        
        .modal-thumbnail {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            opacity: 0.6;
        }
        
        .modal-thumbnail:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
        
        .modal-thumbnail.active {
            border-color: #007bff;
            opacity: 1;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.3);
        }
        
        .modal-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Zoom Controls */
        .zoom-controls {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 10;
        }
        
        .zoom-control-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .zoom-control-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.1);
        }
        
        /* Responsive Modal */
        @media (max-width: 768px) {
            .elegant-header {
                padding: 15px 20px;
            }
            
            .elegant-header .modal-title {
                font-size: 16px;
            }
            
            .modal-nav-btn {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
            
            .modal-prev-btn {
                left: 10px;
            }
            
            .modal-next-btn {
                right: 10px;
            }
            
            .zoom-controls {
                bottom: 10px;
                right: 10px;
            }
            
            .zoom-control-btn {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
            
            .modal-thumbnail {
                width: 50px;
                height: 50px;
            }
            
            .zoom-gallery-container {
                height: 60vh;
            }
        }
        
        /* Elegant Product Info Design */
        .elegant-product-info {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: fit-content;
            border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }
        
        /* Product Title Section */
        .product-title-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .product-title {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.2;
            background: linear-gradient(135deg, #2c3e50, #34495e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .product-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .badge-featured {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        .badge-sale {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .badge-limited {
            background: linear-gradient(135deg, #f39c12, #d68910);
            color: white;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        /* Price Section */
        .price-section {
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            border: 1px solid #dee2e6;
        }
        
        .price-main {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .old-price {
            font-size: 20px;
            color: #6c757d;
            text-decoration: line-through;
            font-weight: 500;
        }
        
        .current-price {
            font-size: 36px;
            font-weight: 800;
            color: #28a745;
            text-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
        }
        
        .discount-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        
        .price-details {
            margin-top: 10px;
        }
        
        .savings {
            color: #28a745;
            font-weight: 600;
            font-size: 16px;
        }
        
        /* Product Info Cards - Compact Design */
        .product-info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }
        
        .stock-card .info-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        
        .sku-card .info-icon {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
        }
        
        .brand-card .info-icon {
            background: linear-gradient(135deg, #fd7e14, #e8590c);
        }
        
        .shipping-card .info-icon {
            background: linear-gradient(135deg, #20c997, #17a2b8);
        }
        
        .return-card .info-icon {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
        }
        
        .warranty-card .info-icon {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .stock-in {
            color: #28a745;
        }
        
        .stock-out {
            color: #dc3545;
        }
        
        .stock-count {
            color: #6c757d;
            font-size: 12px;
            font-weight: 500;
        }
        
        .brand-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        
        .brand-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        
        .shipping-free {
            color: #28a745;
            font-weight: 600;
        }
        
        .shipping-paid {
            color: #fd7e14;
            font-weight: 600;
        }
        
        .shipping-standard {
            color: #6c757d;
        }
        
        .return-available {
            color: #28a745;
            font-weight: 600;
        }
        
        /* Elegant Section Titles */
        .section_title {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
        }
        
        .section_title h2 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .section_title h2 i {
            font-size: 24px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .section-subtitle {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
            margin: 0;
            font-style: italic;
        }
        
        /* Section-specific styling */
        .regular_products .section_title h2 i {
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .sale_products .section_title h2 i {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .related_products .section_title h2 i {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Section backgrounds */
        .regular_products {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 40px 0;
        }
        
        .sale_products {
            background: linear-gradient(135deg, #fff5f5, #ffe6e6);
            padding: 40px 0;
        }
        
        .related_products {
            background: linear-gradient(135deg, #f0f8ff, #e6f3ff);
            padding: 40px 0;
        }
        
        /* Elegant Product View Design */
        .single_product {
            background: #ffffff;
            border-radius: 15px;
            padding: 0;
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .single_product:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        
        .single_product .product_thumb {
            position: relative;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }
        
        .single_product .product_thumb img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: all 0.4s ease;
            border-radius: 15px 15px 0 0;
        }
        
        .single_product:hover .product_thumb img {
            transform: scale(1.05);
        }
        
        /* Elegant Product Labels */
        .label_product {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }
        
        .label_sale, .label_stockout {
            display: inline-block;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .label_sale {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }
        
        .label_stockout {
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }
        
        /* Elegant Action Links */
        .action_links {
            position: absolute;
            top: 15px;
            left: 15px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 3;
        }
        
        .single_product:hover .action_links {
            opacity: 1;
            visibility: visible;
        }
        
        .action_links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .action_links li {
            margin-bottom: 8px;
        }
        
        .quick_button a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            color: #007bff;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .quick_button a:hover {
            background: #007bff;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        
        /* Wishlist and Compare Button Styling */
        .wishlist_button a, .compare_button a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .wishlist_button a:hover {
            background: #e74c3c;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(231,76,60,0.3);
        }
        
        .compare_button a:hover {
            background: #28a745;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(40,167,69,0.3);
        }
        
        .wishlist_button a.active {
            background: #e74c3c;
            color: white;
        }
        
        .compare_button a.active {
            background: #28a745;
            color: white;
        }
        
        .wishlist_button a.disabled, .compare_button a.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Elegant Add to Cart Button */
        .add_to_cart {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            padding: 15px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            border-radius: 0 0 15px 15px;
            z-index: 4; /* Ensure above hover overlay */
        }
        
        .single_product:hover .add_to_cart {
            opacity: 1;
            visibility: visible;
        }
        
        .add_to_cart_btn {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40,167,69,0.3);
        }
        
        .add_to_cart_btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40,167,69,0.4);
        }
        
        .add_to_cart_btn:disabled {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 2px 8px rgba(108,117,125,0.3);
        }
        
        /* Elegant Product Content */
        .product_content {
            padding: 20px;
            text-align: center;
            background: white;
        }
        
        .product_content h3 {
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 12px;
            color: #2c3e50;
        }
        
        .product_content h3 a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .product_content h3 a:hover {
            color: #007bff;
        }
        
        /* Elegant Price Box */
        .price_box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        
        .old_price {
            font-size: 14px;
            color: #6c757d;
            text-decoration: line-through;
            font-weight: 500;
        }
        
        .current_price {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Product Hover Overlay */
        .single_product::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,123,255,0.1), rgba(0,123,255,0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
            border-radius: 15px;
        }
        
        .single_product:hover::before {
            opacity: 1;
        }
        
        /* Elegant Product Modal */
        .elegant-modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
        }
        
        .elegant-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1050;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #6c757d;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .elegant-modal-close:hover {
            background: #e74c3c;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(231,76,60,0.3);
        }
        
        .elegant-modal-close:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(231,76,60,0.2);
        }
        
        .product_modal_body {
            padding: 30px;
            background: transparent;
        }
        
        /* Modal Loading State */
        .product_modal_body .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
            color: #007bff;
        }
        
        /* Modal Error State */
        .product_modal_body .alert {
            border: none;
            border-radius: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            box-shadow: 0 4px 15px rgba(220,53,69,0.1);
        }
        
        .product_desc {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
            line-height: 1.6;
            color: #555;
        }
        
        .product_variant {
            margin-bottom: 20px;
        }
        
        .product_variant label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .product_variant input[type="number"] {
            width: 80px;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            text-align: center;
            margin-right: 15px;
        }
        
        .product_variant input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .product_variant .button {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            /* padding: 15px 35px; */
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            min-width: 180px;
        }
        
        .product_variant .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .product_variant .button:hover::before {
            left: 100%;
        }
        
        .product_variant .button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5);
            background: linear-gradient(135deg, #20c997, #17a2b8);
        }
        
        .product_variant .button:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }
        
        .product_variant .button:disabled {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
            opacity: 0.7;
        }
        
        .product_variant .button:disabled::before {
            display: none;
        }
        
        /* Add to cart button with icon */
        .product_variant .button::after {
            content: '\f07a';
            font-family: 'FontAwesome';
            margin-left: 8px;
            font-size: 14px;
        }
        
        .product_variant .button:disabled::after {
            content: '\f00d';
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .product-gallery-container {
                padding: 15px;
            }
            
            .image-container {
                height: 300px;
            }
            
            .product_d_right {
                padding: 20px;
                margin-top: 20px;
            }
            
            .product_d_right h1 {
                font-size: 24px;
            }
            
            .thumbnail-item {
                width: 60px;
                height: 60px;
            }
            
            .gallery-prev, .gallery-next {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
            
            .product_variant .button {
                /* padding: 12px 25px; */
                font-size: 14px;
                min-width: 150px;
            }
            
            .elegant-close {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            /* Mobile Product Info */
            .elegant-product-info {
                padding: 20px;
            }
            
            .product-title {
                font-size: 24px;
            }
            
            .current-price {
                font-size: 28px;
            }
            
            .product-info-cards {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .info-card {
                padding: 12px;
            }
            
            .info-icon {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
            
            .info-label {
                font-size: 10px;
            }
            
            .info-value {
                font-size: 13px;
            }
            
            /* Mobile Action Buttons */
            .product_d_action ul {
                flex-direction: column;
                gap: 8px;
            }
            
            .product_d_action li {
                min-width: auto;
            }
            
            .product_d_action a {
                padding: 10px 12px;
                font-size: 13px;
            }
            
            .product_d_action a i {
                font-size: 14px;
            }
            
            /* Mobile Statistics */
            .product_stats {
                padding: 12px;
            }
            
            .stat-item {
                padding: 8px;
            }
            
            .stat-item i {
                font-size: 20px;
                margin-bottom: 6px;
            }
            
            .stat-number {
                font-size: 18px;
            }
            
            .stat-label {
                font-size: 10px;
            }
            
            /* Mobile Section Titles */
            .section_title h2 {
                font-size: 22px;
                flex-direction: column;
                gap: 8px;
            }
            
            .section_title h2 i {
                font-size: 20px;
            }
            
            .section-subtitle {
                font-size: 12px;
            }
            
            .regular_products,
            .sale_products,
            .related_products {
                padding: 25px 0;
            }
            
            /* Mobile Product View */
            .single_product {
                margin-bottom: 20px;
            }
            
            .single_product .product_thumb img {
                height: 200px;
            }
            
            .product_content {
                padding: 15px;
            }
            
            .product_content h3 {
                font-size: 14px;
                margin-bottom: 10px;
            }
            
            .current_price {
                font-size: 16px;
            }
            
            .old_price {
                font-size: 12px;
            }
            
            .add_to_cart_btn {
                padding: 10px 16px;
                font-size: 13px;
            }
            
            .quick_button a {
                width: 35px;
                height: 35px;
            }
            
            .label_sale, .label_stockout {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            /* Mobile positioning - sale tag right, action buttons left */
            .label_product {
                top: 12px;
                right: 12px;
            }
            
            .action_links {
                top: 12px;
                left: 12px;
            }
        }
        
        /* Clean up existing styles */
        .product_meta_info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .discount_badge {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        /* Elegant Action Buttons */
        .product_d_action {
            margin-top: 15px;
        }
        
        .product_d_action ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .product_d_action li {
            margin-bottom: 0;
            flex: 1;
            min-width: 120px;
        }
        
        .product_d_action a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6;
            border-radius: 25px;
            text-decoration: none;
            color: #495057;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .product_d_action a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .product_d_action a:hover::before {
            left: 100%;
        }
        
        .product_d_action a:hover {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        
        .product_d_action a i {
            font-size: 16px;
        }
        
        /* Specific button styles */
        .wishlist-btn:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            border-color: #e74c3c !important;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3) !important;
        }
        
        .wishlist-btn.active,
        .wishlist-btn.in-wishlist {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            border-color: #e74c3c !important;
            color: white !important;
        }
        
        .wishlist-btn.active:hover,
        .wishlist-btn.in-wishlist:hover {
            background: linear-gradient(135deg, #c0392b, #a93226) !important;
            border-color: #c0392b !important;
        }
        
        .compare-btn:hover {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3) !important;
        }
        
        .compare-btn.active {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            border-color: #28a745 !important;
            color: white !important;
        }
        
        
        .share-btn:hover {
            background: linear-gradient(135deg, #6f42c1, #5a32a3) !important;
            border-color: #6f42c1 !important;
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3) !important;
        }
        
        .product_meta span {
            display: block;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .shipping_info, .refund_info {
            color: #28a745;
            font-weight: 500;
        }
        
        .badge {
            font-size: 12px;
            padding: 6px 12px;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        /* Elegant Product Statistics */
        .product_stats {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            padding: 15px;
            border-radius: 15px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .product_stats .row {
            margin: 0;
        }
        
        .stat-item {
            padding: 10px;
            text-align: center;
            position: relative;
        }
        
        .stat-item::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, #dee2e6, transparent);
        }
        
        .stat-item:last-child::after {
            display: none;
        }
        
        .stat-item i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
            background: linear-gradient(135deg, #007bff, #0056b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-item .text-info i {
            background: linear-gradient(135deg, #17a2b8, #138496);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-item .text-success i {
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-item .text-warning i {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 4px;
            display: block;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        /* Review Styles */
        .review_stats {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .rating_display {
            padding: 20px;
        }
        
        .rating_number {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            display: block;
            line-height: 1;
        }
        
        .rating_text {
            color: #666;
            margin-top: 10px;
        }
        
        .rating_breakdown {
            padding: 10px 0;
        }
        
        .rating_bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .rating_label {
            width: 60px;
            font-size: 14px;
        }
        
        .rating_progress {
            flex: 1;
            margin: 0 10px;
        }
        
        .rating_count {
            width: 30px;
            text-align: right;
            font-size: 14px;
            color: #666;
        }
        
        .progress {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
        }
        
        .progress-bar {
            background-color: #ffc107;
            border-radius: 4px;
        }
        
        .review_item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        
        .review_item:last-child {
            border-bottom: none;
        }
        
        .review_header {
            margin-bottom: 10px;
        }
        
        .reviewer_info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .review_meta {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .review_date {
            color: #666;
            font-size: 14px;
        }
        
        .review_content p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }
        
        .no_reviews {
            color: #999;
        }
        
        .add_review_section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .review_form_title h3 {
            margin-bottom: 10px;
            color: #333;
        }
        
        .review_form_title p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .star_rating_input {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .rating-star {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .rating-star:hover,
        .rating-star.active {
            color: #ffc107;
        }
        
        .rating_text {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
    </style>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize product gallery
            initializeProductGallery();
            
            // Check wishlist and compare status on page load
            checkWishlistStatus();
            checkCompareStatus();
            
            // Initialize review functionality
            initializeReviewSystem();
            
            // Share button functionality
            $('.share-btn').click(function(e) {
                e.preventDefault();
                $('#shareModal').modal('show');
            });
            
            // Copy URL functionality
            $('#copyUrlBtn').click(function() {
                var urlInput = document.getElementById('productUrl');
                urlInput.select();
                urlInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                
                $(this).text('Copied!').removeClass('btn-outline-secondary').addClass('btn-success');
                setTimeout(() => {
                    $(this).text('Copy').removeClass('btn-success').addClass('btn-outline-secondary');
                }, 2000);
            });
            
            // Wishlist functionality
            $('.wishlist-btn').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                var $btn = $(this);
                var routeToggle = $btn.data('route-toggle');
                
                // Show loading state
                $btn.html('<i class="fa fa-spinner fa-spin"></i> Adding...');
                $btn.prop('disabled', true);
                
                $.ajax({
                    url: routeToggle,
                    method: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.message.includes('added') || response.message.includes('Added')) {
                                $btn.html('<i class="fa fa-heart text-danger"></i> Remove from Wishlist');
                                $btn.addClass('in-wishlist active');
                            } else if (response.message.includes('removed') || response.message.includes('Removed')) {
                                $btn.html('<i class="fa fa-heart"></i> Add to Wishlist');
                                $btn.removeClass('in-wishlist active');
                            }
                            
                            // Update wishlist count in header if exists
                            if (response.wishlist_count !== undefined) {
                                $('.wishlist-count').text(response.wishlist_count);
                            }
                            
                            // Show success message
                            ElegantNotification.success(response.message);
                        } else {
                            ElegantNotification.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        ElegantNotification.error(response ? response.message : 'Something went wrong!');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
            
            // Compare functionality
            $('.compare-btn').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                var $btn = $(this);
                
                // Show loading state
                $btn.html('<i class="fa fa-spinner fa-spin"></i> Adding...');
                $btn.prop('disabled', true);
                
                $.ajax({
                    url: '{{route("frontend_compare_add")}}',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $btn.html('<i class="fa fa-balance-scale text-success"></i> In Compare');
                            $btn.addClass('in-compare active');
                            
                            // Update compare count in header if exists
                            if (response.compare_count !== undefined) {
                                $('.compare-count').text(response.compare_count);
                            }
                            
                            ElegantNotification.success(response.message);
                        } else {
                            ElegantNotification.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        ElegantNotification.error(response ? response.message : 'Something went wrong!');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
        
        // Initialize product gallery
        function initializeProductGallery() {
            // Thumbnail click handler
            $('.thumbnail-item').click(function() {
                var imageUrl = $(this).data('image');
                
                // Update main image
                $('#main-product-img').attr('src', imageUrl);
                $('#zoom-image').attr('src', imageUrl);
                
                // Update active thumbnail
                $('.thumbnail-item').removeClass('active');
                $(this).addClass('active');
            });
            
            // Zoom button click handler
            $('#zoom-btn').click(function() {
                $('#imageZoomModal').modal('show');
                initializeModalGallery();
            });
            
            // Gallery navigation
            $('#gallery-prev').click(function() {
                var activeThumb = $('.thumbnail-item.active');
                var prevThumb = activeThumb.prev('.thumbnail-item');
                
                if (prevThumb.length > 0) {
                    prevThumb.click();
                } else {
                    $('.thumbnail-item:last').click();
                }
            });
            
            $('#gallery-next').click(function() {
                var activeThumb = $('.thumbnail-item.active');
                var nextThumb = activeThumb.next('.thumbnail-item');
                
                if (nextThumb.length > 0) {
                    nextThumb.click();
                } else {
                    $('.thumbnail-item:first').click();
                }
            });
            
            // Keyboard navigation
            $(document).keydown(function(e) {
                if ($('#imageZoomModal').hasClass('show')) {
                    if (e.keyCode === 37) { // Left arrow
                        $('#gallery-prev').click();
                    } else if (e.keyCode === 39) { // Right arrow
                        $('#gallery-next').click();
                    } else if (e.keyCode === 27) { // Escape
                        $('#imageZoomModal').modal('hide');
                    }
                }
            });
            
            // Touch/swipe support for mobile
            let startX = 0;
            let startY = 0;
            
            $('.image-container').on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
                startY = e.originalEvent.touches[0].clientY;
            });
            
            $('.image-container').on('touchend', function(e) {
                if (!startX || !startY) return;
                
                let endX = e.originalEvent.changedTouches[0].clientX;
                let endY = e.originalEvent.changedTouches[0].clientY;
                
                let diffX = startX - endX;
                let diffY = startY - endY;
                
                // Only trigger if horizontal swipe is more significant than vertical
                if (Math.abs(diffX) > Math.abs(diffY)) {
                    if (Math.abs(diffX) > 50) { // Minimum swipe distance
                        if (diffX > 0) {
                            $('#gallery-next').click(); // Swipe left = next image
                        } else {
                            $('#gallery-prev').click(); // Swipe right = previous image
                        }
                    }
                }
                
                startX = 0;
                startY = 0;
            });
        }
        
        // Initialize Modal Gallery
        function initializeModalGallery() {
            let currentImageIndex = 0;
            const totalImages = $('.modal-thumbnail').length;
            let zoomLevel = 1;
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let translateX = 0;
            let translateY = 0;
            
            // Update image counter
            function updateImageCounter() {
                $('#current-image-number').text(currentImageIndex + 1);
                $('#total-images').text(totalImages);
            }
            
            // Change image function
            function changeImage(index) {
                if (index < 0 || index >= totalImages) return;
                
                currentImageIndex = index;
                const $thumbnail = $('.modal-thumbnail').eq(index);
                const imageUrl = $thumbnail.data('image');
                
                // Show loading
                $('#image-loading').addClass('show');
                
                // Update main image
                $('#zoom-image').attr('src', imageUrl);
                
                // Update active thumbnail
                $('.modal-thumbnail').removeClass('active');
                $thumbnail.addClass('active');
                
                // Reset zoom
                resetZoom();
                
                // Update counter
                updateImageCounter();
                
                // Hide loading after image loads
                $('#zoom-image').on('load', function() {
                    $('#image-loading').removeClass('show');
                });
            }
            
            // Reset zoom function
            function resetZoom() {
                zoomLevel = 1;
                translateX = 0;
                translateY = 0;
                $('#zoom-image').css({
                    'transform': 'scale(1) translate(0px, 0px)',
                    'cursor': 'grab'
                });
            }
            
            // Apply zoom
            function applyZoom() {
                $('#zoom-image').css('transform', `scale(${zoomLevel}) translate(${translateX}px, ${translateY}px)`);
            }
            
            // Modal navigation
            $('#modal-prev').click(function() {
                const prevIndex = currentImageIndex > 0 ? currentImageIndex - 1 : totalImages - 1;
                changeImage(prevIndex);
            });
            
            $('#modal-next').click(function() {
                const nextIndex = currentImageIndex < totalImages - 1 ? currentImageIndex + 1 : 0;
                changeImage(nextIndex);
            });
            
            // Modal thumbnail click
            $('.modal-thumbnail').click(function() {
                const index = $(this).data('index');
                changeImage(index);
            });
            
            // Zoom controls
            $('#zoom-in').click(function() {
                zoomLevel = Math.min(zoomLevel * 1.2, 3);
                applyZoom();
            });
            
            $('#zoom-out').click(function() {
                zoomLevel = Math.max(zoomLevel / 1.2, 0.5);
                applyZoom();
            });
            
            $('#zoom-reset').click(function() {
                resetZoom();
            });
            
            // Fullscreen toggle
            $('#fullscreen-toggle').click(function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            });
            
            // Mouse wheel zoom
            $('.zoom-image-container').on('wheel', function(e) {
                e.preventDefault();
                const delta = e.originalEvent.deltaY;
                if (delta < 0) {
                    zoomLevel = Math.min(zoomLevel * 1.1, 3);
                } else {
                    zoomLevel = Math.max(zoomLevel / 1.1, 0.5);
                }
                applyZoom();
            });
            
            // Drag functionality
            $('#zoom-image').on('mousedown', function(e) {
                if (zoomLevel > 1) {
                    isDragging = true;
                    startX = e.clientX - translateX;
                    startY = e.clientY - translateY;
                    $(this).css('cursor', 'grabbing');
                }
            });
            
            $(document).on('mousemove', function(e) {
                if (isDragging && zoomLevel > 1) {
                    translateX = e.clientX - startX;
                    translateY = e.clientY - startY;
                    applyZoom();
                }
            });
            
            $(document).on('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    $('#zoom-image').css('cursor', 'grab');
                }
            });
            
            // Touch support for mobile
            let touchStartX = 0;
            let touchStartY = 0;
            let touchStartTime = 0;
            
            $('.zoom-image-container').on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
                touchStartY = e.originalEvent.touches[0].clientY;
                touchStartTime = Date.now();
            });
            
            $('.zoom-image-container').on('touchend', function(e) {
                if (!touchStartX || !touchStartY) return;
                
                const touchEndX = e.originalEvent.changedTouches[0].clientX;
                const touchEndY = e.originalEvent.changedTouches[0].clientY;
                const touchEndTime = Date.now();
                
                const diffX = touchStartX - touchEndX;
                const diffY = touchStartY - touchEndY;
                const diffTime = touchEndTime - touchStartTime;
                
                // Swipe detection (if not zoomed)
                if (zoomLevel <= 1 && Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50 && diffTime < 300) {
                    if (diffX > 0) {
                        $('#modal-next').click(); // Swipe left = next
                    } else {
                        $('#modal-prev').click(); // Swipe right = prev
                    }
                }
                
                touchStartX = 0;
                touchStartY = 0;
            });
            
            // Keyboard navigation in modal
            $(document).on('keydown.modal', function(e) {
                if ($('#imageZoomModal').hasClass('show')) {
                    switch(e.keyCode) {
                        case 37: // Left arrow
                            $('#modal-prev').click();
                            break;
                        case 39: // Right arrow
                            $('#modal-next').click();
                            break;
                        case 27: // Escape
                            $('#imageZoomModal').modal('hide');
                            break;
                        case 187: // Plus key
                        case 107: // Numpad plus
                            $('#zoom-in').click();
                            break;
                        case 189: // Minus key
                        case 109: // Numpad minus
                            $('#zoom-out').click();
                            break;
                        case 48: // 0 key
                            $('#zoom-reset').click();
                            break;
                    }
                }
            });
            
            // Clean up when modal is hidden
            $('#imageZoomModal').on('hidden.bs.modal', function() {
                $(document).off('keydown.modal');
                resetZoom();
            });
            
            // Initialize
            updateImageCounter();
        }
        
        // Check wishlist status function
        function checkWishlistStatus() {
            // For now, we'll skip the initial check since we don't have a check route
            // The button will show the correct state after the first interaction
        }
        
        // Check compare status function
        function checkCompareStatus() {
            var productId = '{{$product->id}}';
            
            $.ajax({
                url: '{{route("frontend_compare_check")}}',
                method: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.in_compare) {
                        $('.compare-btn').html('<i class="fa fa-balance-scale text-success"></i> In Compare');
                        $('.compare-btn').addClass('in-compare');
                    }
                }
            });
        }
        
        // Initialize review system
        function initializeReviewSystem() {
            // Star rating input
            $('.rating-star').click(function() {
                var rating = $(this).data('rating');
                $('#rating_value').val(rating);
                
                // Update star display
                $('.rating-star').removeClass('active');
                for (var i = 1; i <= rating; i++) {
                    $('.rating-star[data-rating="' + i + '"]').addClass('active');
                }
                
                // Update rating text
                var ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                $('.rating_text').text(ratingTexts[rating] || 'Click to rate');
            });
            
            // Hover effect for stars
            $('.rating-star').hover(
                function() {
                    var rating = $(this).data('rating');
                    $('.rating-star').removeClass('active');
                    for (var i = 1; i <= rating; i++) {
                        $('.rating-star[data-rating="' + i + '"]').addClass('active');
                    }
                },
                function() {
                    var currentRating = $('#rating_value').val();
                    $('.rating-star').removeClass('active');
                    if (currentRating) {
                        for (var i = 1; i <= currentRating; i++) {
                            $('.rating-star[data-rating="' + i + '"]').addClass('active');
                        }
                    }
                }
            );
            
            // Review form submission
            $('#review-form').submit(function(e) {
                e.preventDefault();
                
                var formData = $(this).serialize();
                var $submitBtn = $(this).find('button[type="submit"]');
                
                // Show loading state
                $submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
                $submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: '{{route("frontend_review_store")}}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            ElegantNotification.success(response.message);
                            
                            // Hide form and show success message
                            $('#review-form-container').html(
                                '<div class="alert alert-success">' +
                                '<i class="fa fa-check-circle"></i> ' + response.message +
                                '</div>'
                            );
                            
                            // Update review count
                            updateReviewCount();
                        } else {
                            ElegantNotification.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.errors) {
                            var errorMessage = 'Please fix the following errors:\n';
                            $.each(response.errors, function(field, errors) {
                                errorMessage += '- ' + errors[0] + '\n';
                            });
                            ElegantNotification.error(errorMessage);
                        } else {
                            ElegantNotification.error(response ? response.message : 'Something went wrong!');
                        }
                    },
                    complete: function() {
                        $submitBtn.html('Submit Review');
                        $submitBtn.prop('disabled', false);
                    }
                });
            });
            
            // Load more reviews
            $('#load-more-reviews').click(function() {
                // This would load more reviews via AJAX
                showNotification('Load more reviews functionality will be implemented soon!', 'info');
            });
        }
        
        // Update review count
        function updateReviewCount() {
            $.ajax({
                url: '{{route("frontend_review_stats", $product->id)}}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#review-count').text(response.review_count);
                        $('#average-rating').text(response.average_rating + '/5');
                    }
                }
            });
        }
        
        // Notification system
        function showNotification(message, type) {
            var alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
            var notification = '<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                '<strong>' + (type === 'success' ? 'Success!' : (type === 'error' ? 'Error!' : 'Info!')) + '</strong> ' + message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';
            
            $('body').append(notification);
            
            // Auto remove after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
@endsection
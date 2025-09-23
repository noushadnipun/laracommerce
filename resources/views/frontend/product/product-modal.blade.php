<!-- modal area start-->
 <div class="container">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            
            <div class="modal_tab">  
                <div class="tab-content product-details-large">
                    @php $allImages = $product->getAllImages(); @endphp
                    @if(count($allImages) > 0)
                        @foreach($allImages as $key => $image)
                        <div class="tab-pane fade {{$key == 0 ? 'show active' : ''}}" id="tab{{$key}}" role="tabpanel">
                            <div class="modal_tab_img">
                                <a href="javascript:void(0)"><img src="{{$image['url']}}" alt="" onerror="this.src='{{asset('public/frontend/images/no-images.svg')}}'"></a>    
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="tab-pane fade show active" id="tab0" role="tabpanel">
                            <div class="modal_tab_img">
                                <a href="javascript:void(0)"><img src="{{$product->getFeaturedImageUrl()}}" alt="" onerror="this.src='{{asset('public/frontend/images/no-images.svg')}}'"></a>    
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal_tab_button">    
                    <ul class="nav product_navactive owl-carousel" role="tablist">
                        @if(count($allImages) > 0)
                            @foreach($allImages as $key => $image)
                            <li>
                                <a class="nav-link {{$key == 0 ? 'active' : ''}}" data-toggle="tab" href="#tab{{$key}}" role="tab" aria-controls="tab{{$key}}" aria-selected="false"><img src="{{$image['url']}}" alt="" onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'"></a>
                            </li>
                            @endforeach
                        @else
                            <li>
                                <a class="nav-link active" data-toggle="tab" href="#tab0" role="tab" aria-controls="tab0" aria-selected="true"><img src="{{$product->getFeaturedImageUrl()}}" alt="" onerror="this.src='{{asset('public/frontend/images/no-images.jpg')}}'"></a>
                            </li>
                        @endif
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
                     <span class="old_price"><?php echo !empty($product->sale_price) && $product->sale_price < $product->regular_price ? '৳'.number_format($product->regular_price / 100, 2) : ''?></span>  
                        <span class="current_price"><?php echo !empty($product->sale_price) && $product->sale_price < $product->regular_price ? '৳'.number_format($product->sale_price / 100, 2) : '৳'.number_format($product->regular_price / 100, 2) ;?></span>    
                </div>
                <div class="modal_description mb-15">
                    <?php echo $product->short_description;?>  
                </div> 
                <div class="modal_add_to_cart">
                    <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                    @csrf
                    <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                        <div class="variants_selects">
                            @php
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
                            @endif

                            <div class="modal_add_to_cart">
                                @if($product->current_stock > 0)
                                    <input min="1" max="100" value="1" type="number" name="quantity">
                                    <button type="submit">add to cart</button>
                                @else
                                    <input min="1" max="100" value="1" type="number" name="quantity" disabled>
                                    <button type="button" disabled>sold</button>
                                @endif
                            </div>   
                        </div>
                    </form>
                </div>
                <div class="modal_social">
                    <h2>Share this product</h2>
                    <ul>
                        <li class="whatsapp">
                            <a href="javascript:void(0)" onclick="shareToWhatsApp()" title="Share on WhatsApp">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                        </li>
                        <li class="facebook">
                            <a href="javascript:void(0)" onclick="shareToFacebook()" title="Share on Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="twitter">
                            <a href="javascript:void(0)" onclick="shareToTwitter()" title="Share on Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li class="pinterest">
                            <a href="javascript:void(0)" onclick="shareToPinterest()" title="Share on Pinterest">
                                <i class="fa fa-pinterest"></i>
                            </a>
                        </li>
                        <li class="linkedin">
                            <a href="javascript:void(0)" onclick="shareToLinkedIn()" title="Share on LinkedIn">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        <li class="copy-link">
                            <a href="javascript:void(0)" onclick="copyProductLink()" title="Copy Link">
                                <i class="fa fa-link"></i>
                            </a>
                        </li>
                    </ul>    
                </div>      
            </div>    
        </div>    
    </div>     
</div>
             
<!-- modal area end-->

<!-- Dynamic Share Functionality -->
<style>
/* Enhanced Share Buttons */
.modal_social {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.modal_social h2 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    text-align: center;
}

.modal_social ul {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.modal_social li {
    margin: 0;
}

.modal_social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.modal_social a:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.modal_social a i {
    font-size: 18px;
    transition: all 0.3s ease;
}

/* WhatsApp */
.modal_social .whatsapp a {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
}

.modal_social .whatsapp a:hover {
    background: linear-gradient(135deg, #128C7E, #0F6B5C);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
}

/* Facebook */
.modal_social .facebook a {
    background: linear-gradient(135deg, #1877F2, #0A5FCC);
    color: white;
}

.modal_social .facebook a:hover {
    background: linear-gradient(135deg, #0A5FCC, #0847A3);
    box-shadow: 0 6px 20px rgba(24, 119, 242, 0.4);
}

/* Twitter */
.modal_social .twitter a {
    background: linear-gradient(135deg, #1DA1F2, #0D8BD9);
    color: white;
}

.modal_social .twitter a:hover {
    background: linear-gradient(135deg, #0D8BD9, #0A6BB3);
    box-shadow: 0 6px 20px rgba(29, 161, 242, 0.4);
}

/* Pinterest */
.modal_social .pinterest a {
    background: linear-gradient(135deg, #E60023, #CC001F);
    color: white;
}

.modal_social .pinterest a:hover {
    background: linear-gradient(135deg, #CC001F, #B3001B);
    box-shadow: 0 6px 20px rgba(230, 0, 35, 0.4);
}

/* LinkedIn */
.modal_social .linkedin a {
    background: linear-gradient(135deg, #0077B5, #005885);
    color: white;
}

.modal_social .linkedin a:hover {
    background: linear-gradient(135deg, #005885, #004066);
    box-shadow: 0 6px 20px rgba(0, 119, 181, 0.4);
}

/* Copy Link */
.modal_social .copy-link a {
    background: linear-gradient(135deg, #6C757D, #495057);
    color: white;
}

.modal_social .copy-link a:hover {
    background: linear-gradient(135deg, #495057, #343A40);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

/* Ripple Effect */
.modal_social a::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.modal_social a:active::before {
    width: 300px;
    height: 300px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal_social ul {
        gap: 10px;
    }
    
    .modal_social a {
        width: 40px;
        height: 40px;
    }
    
    .modal_social a i {
        font-size: 16px;
    }
}
</style>

<script>
// Product sharing data
const productData = {
    title: '{{ $product->title }}',
    description: '{{ strip_tags($product->short_description) }}',
    price: '{{ $product->sale_price && $product->sale_price < $product->regular_price ? number_format($product->sale_price / 100, 2) : number_format($product->regular_price / 100, 2) }}',
    image: '{{ $product->getFeaturedImageUrl() }}',
    url: '{{ route("frontend_single_product", $product->slug) }}'
};

// WhatsApp Share
function shareToWhatsApp() {
    const message = `Check out this amazing product: ${productData.title}\n\nPrice: ৳${productData.price}\n\n${productData.description}\n\nView product: ${productData.url}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

// Facebook Share
function shareToFacebook() {
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productData.url)}`;
    window.open(facebookUrl, '_blank', 'width=600,height=400');
}

// Twitter Share
function shareToTwitter() {
    const tweetText = `Check out this amazing product: ${productData.title} - ৳${productData.price}`;
    const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(tweetText)}&url=${encodeURIComponent(productData.url)}`;
    window.open(twitterUrl, '_blank', 'width=600,height=400');
}

// Pinterest Share
function shareToPinterest() {
    const pinterestUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(productData.url)}&media=${encodeURIComponent(productData.image)}&description=${encodeURIComponent(productData.title)}`;
    window.open(pinterestUrl, '_blank', 'width=600,height=400');
}

// LinkedIn Share
function shareToLinkedIn() {
    const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(productData.url)}`;
    window.open(linkedinUrl, '_blank', 'width=600,height=400');
}

// Copy Product Link
function copyProductLink() {
    navigator.clipboard.writeText(productData.url).then(function() {
        // Show success notification
        if (typeof ElegantNotification !== 'undefined') {
            ElegantNotification.success('Product link copied to clipboard!');
        } else {
            alert('Product link copied to clipboard!');
        }
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = productData.url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (typeof ElegantNotification !== 'undefined') {
            ElegantNotification.success('Product link copied to clipboard!');
        } else {
            alert('Product link copied to clipboard!');
        }
    });
}

// Enhanced share with native sharing if available
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: productData.title,
            text: productData.description,
            url: productData.url
        }).catch(console.error);
    } else {
        // Fallback to copy link
        copyProductLink();
    }
}
</script>



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

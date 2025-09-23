<?php
namespace App\Helpers\Frontend;

use \App\Models\Product;

class ProductView {

     /**
     * Price Sign BDT
     */
    public static function priceSign($price){
        return '৳'.$price;
    }

     /**
     * Product View Desoign
     */

    public static function view($query = '', $noQuery = ''){
       ob_start();
       foreach($query as $product) { 
        ?>
        <article class="single_product">
            <figure>
                <div class="product_thumb">
                    <a class="primary_img" href="<?php echo route('frontend_single_product', $product->slug);?>">
                        <img src="<?php echo $product->getFeaturedImageUrl(); ?>" 
                             alt="<?php echo htmlspecialchars($product->title, ENT_QUOTES); ?>" 
                             loading="lazy" 
                             decoding="async" 
                             fetchpriority="low">
                    </a>
                    <div class="label_product">
                    <?php 
                        $isOutOfStock = isset($product->current_stock) ? ((int)$product->current_stock <= 0) : (method_exists($product, 'isOutOfStock') ? $product->isOutOfStock() : false);
                        if($isOutOfStock){
                            echo '<span class="label_stockout">sold</span>';
                        } else if(!empty($product->sale_price)) {
                            echo '<span class="label_sale">sale</span>';
                        }
                    ?>
                    </div>
                    <div class="action_links">
                        <ul>
                            <li class="quick_button"><a id="<?php echo $product->id;?>" class="modalQuickView" href="javascript:void()" data-toggle="modal" data-target="#modal_box" title="quick view" data-product-id="<?php echo $product->id;?>"> <span class="ion-ios-search-strong"></span></a></li>
                            <li class="wishlist_button">
                                <?php 
                                $isWishlisted = false;
                                if(auth()->check()) {
                                    $isWishlisted = \App\Models\Product\ProductWishlist::isInWishlist(auth()->id(), $product->id);
                                }
                                ?>
                                <a href="javascript:void(0)" class="wishlistToggle <?php echo $isWishlisted ? 'active in-wishlist' : ''; ?>" data-product-id="<?php echo $product->id; ?>" title="<?php echo $isWishlisted ? 'remove from wishlist' : 'add to wishlist'; ?>">
                                    <span class="ion-ios-heart<?php echo $isWishlisted ? '' : '-outline'; ?>"></span>
                                </a>
                            </li>
                            <li class="compare_button"><a href="javascript:void(0)" class="compareAdd" data-product-id="<?php echo $product->id; ?>" title="add to compare"><span class="ion-android-options"></span></a></li>
                        </ul>
                    </div>
                    <div class="add_to_cart">
                        <?php if(!$isOutOfStock){ ?>
                        <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                            <?php
                            if(isset($product['attribute'])){ ?>
                                <a id="<?php echo $product->id;?>" class="add_to_cart_btn modalQuickView" type="button" title="add to cart" data-toggle="modal" data-target="#modal_box">Add to cart</a>
                            <?php }else{ ?>
                                <button class="add_to_cart_btn" type="submit" title="add to cart">Add to cart</button>
                            <?php } ?>
                        </form>
                        <?php } else { ?>
                            <button class="add_to_cart_btn" type="button" title="stock out" disabled>Stock Out</button>
                        <?php } ?>
                    </div>
                </div>
                <figcaption class="product_content">
                    <div class="price_box">
                        <span class="old_price"><?php echo !empty($product->sale_price) && $product->sale_price < $product->regular_price ? '৳'.number_format($product->regular_price / 100, 2) : ''?></span>  
                        <span class="current_price"><?php echo !empty($product->sale_price) && $product->sale_price < $product->regular_price ? '৳'.number_format($product->sale_price / 100, 2) : '৳'.number_format($product->regular_price / 100, 2) ;?></span>  
                    </div>
                    <h3 class="product_name"><a href="<?php echo route('frontend_single_product', $product->slug);?>"><?php echo $product->title;?></a></h3>
                </figcaption>
            </figure>
        </article>
        <?php
                
            } 
        ?>
        <?php
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
    }

    /**
     * Render only the add to cart button for a product
     */
    public static function addToCartButton($product) {
        ob_start();
        $isOutOfStock = isset($product->current_stock) ? ((int)$product->current_stock <= 0) : (method_exists($product, 'isOutOfStock') ? $product->isOutOfStock() : false);
        ?>
        <div class="add_to_cart">
            <?php if(!$isOutOfStock){ ?>
            <form method="POST" action="<?php echo route('frontend_cart_store');?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                <?php
                if(isset($product['attribute'])){ ?>
                    <a id="<?php echo $product->id;?>" class="add_to_cart_btn modalQuickView" type="button" title="add to cart" data-toggle="modal" data-target="#modal_box">Add to cart</a>
                <?php }else{ ?>
                    <button class="add_to_cart_btn" type="submit" title="add to cart">Add to cart</button>
                <?php } ?>
            </form>
            <?php } else { ?>
                <button class="add_to_cart_btn" type="button" title="stock out" disabled>Stock Out</button>
            <?php } ?>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function productModal(){
        ob_start();?>

        <!-- Elegant Modal Area Start -->
        <div class="modal fade" id="modal_box" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content elegant-modal-content">
                    <button type="button" class="close elegant-modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal_body product_modal_body">
                        <!-- Content will be loaded here -->
                    </div>    
                </div>
            </div>
        </div>
        <!-- Elegant Modal Area End -->
        
        <!-- Elegant Modal CSS -->
        <style>
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
        
        /* Wishlist Active State */
        .wishlistToggle.active,
        .wishlistToggle.in-wishlist {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            border-color: #e74c3c !important;
            color: white !important;
        }
        
        .wishlistToggle.active:hover,
        .wishlistToggle.in-wishlist:hover {
            background: linear-gradient(135deg, #c0392b, #a93226) !important;
            border-color: #c0392b !important;
        }
        
        .wishlistToggle.active span,
        .wishlistToggle.in-wishlist span {
            color: white !important;
        }
        
        /* Compare Active State */
        .compareAdd.active {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            border-color: #28a745 !important;
            color: white !important;
        }
        
        .compareAdd.active span {
            color: white !important;
        }
        </style>
        

        <script>
            // Only initialize handlers once
            if (!window.ProductViewHandlersInitialized) {
                window.ProductViewHandlersInitialized = true;
                
            $(document).ready(function(){
                $('.modalQuickView').click(function(e){
                    e.preventDefault();
                    let productId = $(this).attr('id') || $(this).data('product-id');
                    console.log('Quick view clicked for product:', productId);
                    console.log('Element:', $(this));
                    
                    // Validate product ID
                    if (!productId || productId === '' || productId === 'undefined') {
                        console.error('Invalid product ID:', productId);
                        ElegantNotification.error('Invalid product ID');
                        return;
                    }
                    
                    // Show loading state
                    $('.product_modal_body').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading product details...</p></div>');
                    $('#modal_box').modal('show');
                    
                    $.ajax({
                        type : 'GET',
                        url :  '<?php echo url('product/quick-view');?>/'+productId,
                        timeout: 10000, // 10 second timeout
                        success : function(data){
                            $('.product_modal_body').html(data);
                            console.log('Modal content loaded successfully for product:', productId);
                        },
                        error: function(xhr, status, error) {
                            console.error('Quick view error:', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                responseText: xhr.responseText,
                                productId: productId
                            });
                            
                            let errorMessage = 'Error loading product details.';
                            
                            if (xhr.status === 404) {
                                errorMessage = 'Product not found.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Server error. Please try again.';
                            } else if (status === 'timeout') {
                                errorMessage = 'Request timeout. Please try again.';
                            }
                            
                            $('.product_modal_body').html(`
                                <div class="alert alert-danger text-center">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <h5>Error</h5>
                                    <p>${errorMessage}</p>
                                    <button class="btn btn-primary btn-sm" onclick="$('#modal_box').modal('hide')">Close</button>
                                </div>
                            `);
                            
                            ElegantNotification.error(errorMessage);
                        }
                    })
                })

                    // Wishlist toggle
                    $(document).on('click', '.wishlistToggle', function(e){
                        e.preventDefault();
                        var productId = $(this).data('product-id');
                        var $btn = $(this);
                        console.log('Wishlist clicked for product:', productId);
                        $btn.addClass('disabled');
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo route('frontend_wishlist_toggle'); ?>',
                            data: { product_id: productId, _token: '<?php echo csrf_token(); ?>' },
                            success: function(resp){
                                console.log('Wishlist response:', resp);
                                if(resp.success) {
                                    // Toggle active state and icon
                                    $btn.toggleClass('active in-wishlist');
                                    
                                    // Update icon and title
                                    var $icon = $btn.find('span');
                                    var currentIcon = $icon.attr('class');
                                    
                                    if(currentIcon.includes('outline')) {
                                        // Added to wishlist
                                        $icon.removeClass('ion-ios-heart-outline').addClass('ion-ios-heart');
                                        $btn.attr('title', 'remove from wishlist');
                                    } else {
                                        // Removed from wishlist
                                        $icon.removeClass('ion-ios-heart').addClass('ion-ios-heart-outline');
                                        $btn.attr('title', 'add to wishlist');
                                    }
                                    
                                    // Show success message
                                    if(resp.message) {
                                        ElegantNotification.success(resp.message);
                                    }
                                }
                            },
                            error: function(xhr){
                                console.log('Wishlist error:', xhr.responseText);
                                if(xhr.status === 401){
                                    window.location.href = '<?php echo route('frontend_customer_login'); ?>';
                                } else {
                                    ElegantNotification.error('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                                }
                            },
                            complete: function(){
                                $btn.removeClass('disabled');
                            }
                        });
                    });

                    // Compare add
                    $(document).on('click', '.compareAdd', function(e){
                        e.preventDefault();
                        var productId = $(this).data('product-id');
                        var $btn = $(this);
                        console.log('Compare clicked for product:', productId);
                        $btn.addClass('disabled');
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo route('frontend_compare_add'); ?>',
                            data: { product_id: productId, _token: '<?php echo csrf_token(); ?>' },
                            success: function(resp){
                                console.log('Compare response:', resp);
                                $btn.addClass('active');
                                if(resp.success) {
                                    // Show success message
                                    if(resp.message) {
                                        ElegantNotification.success(resp.message);
                                    }
                                }
                            },
                            error: function(xhr){
                                console.log('Compare error:', xhr.responseText);
                                if(xhr.responseJSON?.message) {
                                    ElegantNotification.error('Error: ' + xhr.responseJSON.message);
                                } else {
                                    ElegantNotification.error('Error adding to compare list');
                                }
                            },
                            complete: function(){
                                $btn.removeClass('disabled');
                            }
                        });
                    });
                });
            }
        </script>
        

        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}








?>
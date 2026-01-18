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
                    <a class="primary_img" href="<?php echo route('frontend_single_product', $product->slug);?>" data-product-id="<?php echo $product->id;?>">
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
                                <a id="<?php echo $product->id;?>" class="add_to_cart_btn modalQuickView" type="button" title="add to cart" data-toggle="modal" data-target="#modal_box" data-product-id="<?php echo $product->id;?>">Add to cart</a>
                            <?php }else{ ?>
                                <button class="add_to_cart_btn" type="submit" title="add to cart" data-product-id="<?php echo $product->id;?>">Add to cart</button>
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
                    <a id="<?php echo $product->id;?>" class="add_to_cart_btn modalQuickView" type="button" title="add to cart" data-toggle="modal" data-target="#modal_box" data-product-id="<?php echo $product->id;?>">Add to cart</a>
                <?php }else{ ?>
                    <button class="add_to_cart_btn" type="submit" title="add to cart" data-product-id="<?php echo $product->id;?>">Add to cart</button>
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
        
        <!-- Styles and scripts moved to public assets -->
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}








?>
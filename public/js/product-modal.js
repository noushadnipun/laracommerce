// Product modal interactions extracted from ProductView helper
(function(){
    if (window.ProductViewHandlersInitialized) return;
    window.ProductViewHandlersInitialized = true;

    $(document).ready(function(){
        $(document).on('click', '.modalQuickView', function(e){
            e.preventDefault();
            var productId = $(this).attr('id') || $(this).data('product-id');
            if (!productId) {
                ElegantNotification && ElegantNotification.error('Invalid product ID');
                return;
            }
            $('.product_modal_body').html('<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading product details...</p></div>');
            $('#modal_box').modal('show');
            $.ajax({
                type: 'GET',
                url: window.LARA_PRODUCT_QUICK_VIEW_URL ? (window.LARA_PRODUCT_QUICK_VIEW_URL + '/' + productId) : ('/product/quick-view/' + productId),
                timeout: 10000,
                success: function(data){
                    $('.product_modal_body').html(data);
                },
                error: function(xhr, status){
                    var msg = 'Error loading product details.';
                    if (xhr.status === 404) msg = 'Product not found.';
                    else if (status === 'timeout') msg = 'Request timeout. Please try again.';
                    $('.product_modal_body').html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i><h5>Error</h5><p>'+msg+'</p><button class="btn btn-primary btn-sm" onclick="$(\'#modal_box\').modal(\'hide\')">Close</button></div>');
                    ElegantNotification && ElegantNotification.error(msg);
                }
            });
        });

        $(document).on('click', '.wishlistToggle', function(e){
            e.preventDefault();
            var productId = $(this).data('product-id');
            var $btn = $(this);
            $btn.addClass('disabled');
            $.ajax({
                type:'POST',
                url: window.LARA_WISHLIST_TOGGLE_URL || $('meta[name="wishlist-toggle-url"]').attr('content') || '',
                data: { product_id: productId, _token: window.Laravel && Laravel.csrfToken ? Laravel.csrfToken : $('meta[name="csrf-token"]').attr('content') },
                success: function(resp){
                    $btn.toggleClass('active in-wishlist');
                    var $icon = $btn.find('span');
                    if($icon.hasClass('ion-ios-heart-outline')){
                        $icon.removeClass('ion-ios-heart-outline').addClass('ion-ios-heart');
                        $btn.attr('title','remove from wishlist');
                    }else{
                        $icon.removeClass('ion-ios-heart').addClass('ion-ios-heart-outline');
                        $btn.attr('title','add to wishlist');
                    }
                    resp && resp.message && ElegantNotification && ElegantNotification.success(resp.message);
                },
                error: function(xhr){
                    if(xhr.status === 401){ window.location.href = window.LARA_LOGIN_URL || '/customer/login'; return; }
                    ElegantNotification && ElegantNotification.error('Error: ' + ((xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong'));
                },
                complete: function(){ $btn.removeClass('disabled'); }
            });
        });

        $(document).on('click', '.compareAdd', function(e){
            e.preventDefault();
            var productId = $(this).data('product-id');
            var $btn = $(this);
            $btn.addClass('disabled');
            $.ajax({
                type:'POST',
                url: window.LARA_COMPARE_ADD_URL || $('meta[name="compare-add-url"]').attr('content') || '',
                data: { product_id: productId, _token: window.Laravel && Laravel.csrfToken ? Laravel.csrfToken : $('meta[name="csrf-token"]').attr('content') },
                success: function(resp){
                    $btn.addClass('active');
                    resp && resp.message && ElegantNotification && ElegantNotification.success(resp.message);
                },
                error: function(xhr){
                    ElegantNotification && ElegantNotification.error('Error adding to compare list');
                },
                complete: function(){ $btn.removeClass('disabled'); }
            });
        });

        // Ensure backdrop/body state is cleaned up on close
        $('#modal_box').on('hidden.bs.modal', function(){
            try {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').removeAttr('style');
                $('.product_modal_body').empty();
            } catch (e) {}
        });
    });
})();



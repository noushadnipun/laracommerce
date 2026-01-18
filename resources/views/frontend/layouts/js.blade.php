<!-- Plugins JS -->
<script src="{{asset('/public/frontend/assets/js/plugins.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('/public/frontend/assets/js/main.js')}}"></script>


<?php echo \App\Helpers\Frontend\ProductView::productModal(); ?>
<link rel="stylesheet" href="{{ asset('public/css/product-modal.css') }}">
<script src="{{ asset('public/js/product-modal.js') }}"></script>
<script src="{{ asset('public/js/product-click-tracking.js') }}"></script>
@php
    $idsJson = \App\Helpers\WebsiteSettings::settings('recently_viewed');
    $ids = $idsJson ? json_decode($idsJson, true) : [];
    if (empty($ids) && session()->has('recently_viewed_products')) {
        $ids = array_slice((array) session('recently_viewed_products'), 0, 20);
    }
    $products = collect();
    if (!empty($ids)) {
        $products = \App\Models\Product::whereIn('id', $ids)
            ->where('visibility', '1')
            ->orderBy('created_at', 'DESC')
            ->get();
    }
@endphp
@if($products->count())
<section class="product_area mb-46">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="section_title">
					<h2>Recently Viewed</h2>
				</div>
			</div>
		</div>
		<div class="product_carousel product_column5 owl-carousel">
			{!! \App\Helpers\Frontend\ProductView::view($products) !!}
		</div>
	</div>
</section>
@endif




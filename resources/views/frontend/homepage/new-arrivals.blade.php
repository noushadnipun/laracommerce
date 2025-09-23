@php
    $limitJson = \App\Helpers\WebsiteSettings::settings('new_arrivals');
    $limit = $limitJson ? (int) $limitJson : 20;
    $products = \App\Models\Product::where('visibility', '1')
        ->orderBy('created_at', 'DESC')
        ->limit($limit)->get();
@endphp
@if($products->count())
<section class="product_area mb-46">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="section_title">
					<h2>New Arrivals</h2>
				</div>
			</div>
		</div>
		<div class="product_carousel product_column5 owl-carousel">
			{!! \App\Helpers\Frontend\ProductView::view($products) !!}
		</div>
	</div>
</section>
@endif




@php
    $promoStripsJson = \App\Helpers\WebsiteSettings::settings('promo_strips');
    $promoStrips = $promoStripsJson ? json_decode($promoStripsJson, true) : [];
@endphp
@if(!empty($promoStrips))
<section class="shipping_area shipping_four mb-70">
	<div class="container-fluid">
		<div class="shipping_inner">
			<div class=" row no-gutters">
				@foreach($promoStrips as $strip)
					<div class="col-lg-3 col-md-6">
						<div class="single_shipping">
							<div class="shipping_icone">
								@if(!empty($strip['icon']))
									<i class="fa {{ $strip['icon'] }}" aria-hidden="true"></i>
								@elseif(!empty($strip['image']))
									<img src="{{ $strip['image'] }}" alt="">
								@endif
							</div>
							<div class="shipping_content">
								<h2>{{ $strip['title'] ?? '' }}</h2>
								<p>{{ $strip['subtitle'] ?? '' }}</p>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</section>
@endif




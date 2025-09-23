@php
    $seoBlock = \App\Helpers\WebsiteSettings::settings('homepage_seo_block');
@endphp
@if(!empty($seoBlock))
<section class="seo_block_area mb-70">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="seo_content">
					{!! $seoBlock !!}
				</div>
			</div>
		</div>
	</div>
</section>
@endif




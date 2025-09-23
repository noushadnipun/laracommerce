<aside class="sidebar_widget">
    <div class="widget_inner">
        <div class="widget_list widget_categories">
            @php $getProductCats = \App\Models\ProductCategory::where('visibility', '1')->where('parent_id', null)->orderBy('id', 'DESC')->get(); @endphp
            <h2>Product categories</h2>
            <ul>
                @foreach($getProductCats as $pCat)
                    @php $checkSubCat = \App\Models\ProductCategory::where('visibility', '1')->where('parent_id', $pCat->id)->get();  @endphp

                    <li class="{{count($checkSubCat) > 0 ? 'widget_sub_categories' : ''}}">

                        <a href="{{count($checkSubCat) > 0 ? 'javascript:void(0)' : route('frontend_single_product_category', $pCat->slug)}}">
                            {{$pCat->name}}
                        </a>
                        @if(count($checkSubCat) > 0)
                        <ul class="widget_dropdown_categories">
                            @foreach($checkSubCat as $sCat)
                            <li><a href="{{route('frontend_single_product_category', $sCat->slug)}}">{{$sCat->name}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="widget_list widget_filter">
            <h2>Filter by price</h2>
            <form action="{{route('frontend_filter_price')}}" method="get"> 
                @csrf
                <div id="slider-range"></div>   
                <button type="submit">Filter</button>
                <input type="text" name="amount" id="amount" />   
            </form> 
        </div>

        <div class="widget_list tags_widget">
            <h2>Product brands</h2>
            <div class="tag_cloud" style="max-height: 220px; overflow: hidden; position: relative;" id="brandTagCloud">
                @php $brands = App\Models\ProductBrand::where('visibility', '1')->orderBy('name')->get() @endphp
                @foreach ($brands as $index => $item)
                    <a class="brand-tag {{ $index >= 20 ? 'd-none extra-brand' : '' }}" href="{{route('frontend_single_product_brand', $item->slug)}}">{{$item->name}}</a>
                @endforeach
            </div>
            @if(count($brands) > 20)
            <div class="mt-2">
                <a href="javascript:void(0)" id="toggleBrands" class="btn btn-sm btn-outline-secondary">Show more</a>
            </div>
            @endif
        </div>
    </div>
</aside>
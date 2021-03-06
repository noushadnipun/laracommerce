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
            <div class="tag_cloud">
                @php $brands = App\Models\ProductBrand::where('visibility', '1')->orderBy('id', 'DESC')->get() @endphp
                @foreach ($brands as $item)
                    <a href="{{route('frontend_single_product_brand', $item->slug)}}">{{$item->name}}</a>
                @endforeach
            </div>
        </div>
    </div>
</aside>
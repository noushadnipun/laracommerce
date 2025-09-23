@if(isset($menu) && !empty($menu['items']))
<ul @foreach($menu['attributes'] as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
    @foreach($menu['items'] as $item)
        <li @if(isset($item['options']['class'])) class="{{ $item['options']['class'] }}" @endif>
            <a href="{{ $item['url'] }}" 
               @if(isset($item['options']['link_class'])) class="{{ $item['options']['link_class'] }}" @endif>
                {{ $item['title'] }}
            </a>
            
            @if(!empty($item['children']))
                <ul class="submenu">
                    @foreach($item['children'] as $child)
                        <li @if(isset($child['options']['class'])) class="{{ $child['options']['class'] }}" @endif>
                            <a href="{{ $child['url'] }}" 
                               @if(isset($child['options']['link_class'])) class="{{ $child['options']['link_class'] }}" @endif>
                                {{ $child['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
@endif












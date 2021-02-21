<li class="nav-item">
    <a href="{{route('admin_dashboard')}}" class="nav-link {{ Request()->routeIs('admin_dashboard') ? 'active' : ' ' }}">
        <i class="nav-icon fas fa-tachometer-alt text-sm"></i>
        <p> Dashboard </p>
    </a>
</li>

@php $showAllTerm = \App\Models\Term::get();@endphp

@foreach($showAllTerm as $term)
<li class="nav-item has-treeview {{ Request()->is('admin/term_type='.$term->slug.'*') ? 'menu-open' : ' ' }}">
    <a href="" class="nav-link {{ Request()->is('admin/term_type='.$term->slug.'*') ? 'active' : ' ' }}">
        <i class="nav-icon fas fa-list  text-sm"></i>
        <p>{{$term->name}}<i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('admin_term_type_index', $term->slug)}}" class="nav-link {{ Request()->is('admin/term_type='.$term->slug.'/all') ? 'active' : ' ' }}">
                <i class="nav-icon fas far fa-dot-circle font-11"></i>
                <p> All {{$term->name}} </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('admin_term_type_form', $term->slug)}}" class="nav-link {{ Request()->is('admin/term_type='.$term->slug.'/create') ? 'active' : ' ' }}">
                <i class="nav-icon far fa-dot-circle font-11"></i>
                <p> Add New </p>
            </a>
        </li>

        @php $getTaxonomy = \App\Models\TermTaxonomy::where('term_type', $term->slug)->get(); @endphp


        @foreach($getTaxonomy as $taxonomy)
        <li class="nav-item">
            <a href="{{route('admin_taxonomy_type_index', [$term->slug, $taxonomy->slug])}}" class="nav-link {{ Request()->is('admin/term_type='.$term->slug.'/taxonomy='.$taxonomy->slug.'/all') ? 'active' : ' ' }}">
                <i class="nav-icon far fa-dot-circle font-11"></i>
                <p> {{$taxonomy->name}} </p>
            </a>
        </li>
        @endforeach


    </ul>
</li>
@endforeach


 <li class="nav-item">
    <a href="{{route('admin_adminMenu')}}" class="nav-link">
        <i class="nav-icon far fa-dot-circle font-11"></i>
        <p>
        Menu
        </p>
    </a>
 </li>


<li class="nav-item has-treeview {{ Request()->routeIs('admin_product*') ? 'menu-open' : ' ' }}">
    <a href="" class="nav-link {{ Request()->routeIs('admin_product*') ? 'active' : ' ' }}">
        <i class="nav-icon fas fa-shopping-cart  text-sm"></i>
        <p>Product<i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{route('admin_product_brand_index')}}" class="nav-link {{ Request()->routeIs('admin_product_brand_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
                <p>Brand</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('admin_product_category_index')}}" class="nav-link {{ Request()->routeIs('admin_product_category_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
                <p>Category</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('admin_product_index')}}" class="nav-link {{ Request()->routeIs('admin_product_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
                <p>All Products</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('admin_product_create')}}" class="nav-link {{ Request()->routeIs('admin_product_create') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
            <p>Add New</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('admin_product_order_index')}}" class="nav-link {{ Request()->routeIs('admin_product_order_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
            <p>Manage Order</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{route('admin_product_coupon_index')}}" class="nav-link {{ Request()->routeIs('admin_product_coupon_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
            <p>Coupon</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('admin_product_store_settings_index')}}" class="nav-link {{ Request()->routeIs('admin_product_store_settings_index') ? 'active' : ' ' }}">
            <i class="nav-icon far fa-dot-circle font-11"></i>
            <p>Store Settings</p>
            </a>
        </li>
    </ul>
</li>








<!-- Settings -->
<li class="nav-item">
    <a href="{{route('admin_frontend_settings_index')}}" class="nav-link {{ Request()->routeIs('admin_frontend_settings_index') ? 'active' : ' ' }}">
        <i class="nav-icon fas fa-cog text-sm"></i>
        <p> Frontend Settings </p>
    </a>
</li>

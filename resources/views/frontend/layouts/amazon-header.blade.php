<?php 
$companyPhone = \App\Helpers\WebsiteSettings::settings('company_phone');
$getProductCats = \App\Models\ProductCategory::where('visibility', '1')->where('parent_id', null)->get();
$cart = session()->get('cart', []);
$cartCount = count($cart);
?>

<!-- Modern E-commerce Header -->
<header class="modern-header">
    <!-- Top Bar -->
    <div class="header-top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="contact-info">
                        <span><i class="fa fa-phone"></i> {{ $companyPhone }}</span>
                        <span><i class="fa fa-envelope"></i> support@laracommerce.com</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-links">
                        @if(Auth::check())
                            <a href="{{ route('frontend_customer_dashboard') }}"><i class="fa fa-user"></i> {{ Auth::user()->name }}</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout</a>
                        @else
                            <a href="{{ route('frontend_customer_login') }}"><i class="fa fa-sign-in"></i> Sign In</a>
                            <a href="{{ route('frontend_customer_login') }}"><i class="fa fa-user-plus"></i> Register</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="header-main">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="brand-logo">
                        <a href="{{url('/')}}">
                            <img src="{{ \App\Helpers\websiteSettings::siteLogo() }}" alt="LaraCommerce" class="logo-img">
                            <span class="brand-text">LaraCommerce</span>
                        </a>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="search-section">
                        <form action="{{route('frontend_search')}}" method="get" class="search-form">
                            <div class="search-wrapper">
                                <div class="category-selector">
                                    <select name="category" id="search-category">
                                        <option value="">All Categories</option>
                                        @foreach($getProductCats as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa fa-chevron-down"></i>
                                </div>
                                <div class="search-input-wrapper">
                                    <input type="text" name="search" placeholder="Search for products, brands and more..." value="{{ request('search') }}" class="search-input">
                                    <button type="submit" class="search-button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-lg-3 col-md-12 col-6">
                    <div class="header-actions">
                        <div class="action-buttons">
                            <!-- Wishlist -->
                            <a href="{{ route('frontend_wishlist_index') }}" class="action-btn wishlist-btn">
                                <i class="fa fa-heart"></i>
                                <span>Wishlist</span>
                            </a>
                            
                            <!-- Compare -->
                            <a href="{{ route('frontend_compare_index') }}" class="action-btn compare-btn">
                                <i class="fa fa-balance-scale"></i>
                                <span>Compare</span>
                            </a>
                            
                            <!-- Cart -->
                            <a href="{{ route('frontend_cart_index') }}" class="action-btn cart-btn">
                                <div class="cart-icon-wrapper">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="cart-badge">{{ $cartCount }}</span>
                                </div>
                                <div class="cart-info">
                                    <span class="cart-text">Cart</span>
                                    <span class="cart-total">৳{{ number_format(array_sum(array_column($cart, 'price')) / 100, 2) }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="header-navigation">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Categories Menu -->
                <div class="col-lg-3 col-md-4">
                    <div class="categories-menu">
                        <button class="categories-toggle" id="categories-toggle">
                            <i class="fa fa-bars"></i>
                            <span>Browse Categories</span>
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <div class="categories-dropdown" id="categories-dropdown">
                            <ul class="categories-list">
                                @foreach($getProductCats as $category)
                                    <li>
                                        <a href="{{ route('frontend_single_product_category', $category->slug) }}">
                                            <i class="fa fa-{{ $category->icon ?? 'tag' }}"></i>
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Navigation -->
                <div class="col-lg-9 col-md-8">
                    <nav class="main-navigation">
                        <ul class="nav-menu">
                            <li><a href="{{ route('frontend_products') }}?sale=1" class="nav-link hot-deals">🔥 Hot Deals</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">New Arrivals</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">Best Sellers</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">Electronics</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">Fashion</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">Home & Garden</a></li>
                            <li><a href="{{ route('frontend_products') }}" class="nav-link">Sports</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <div class="mobile-menu-toggle d-lg-none">
        <button class="mobile-toggle-btn" id="mobile-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>


<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay">
    <div class="mobile-menu-content">
        <div class="mobile-menu-header">
            <h3>Menu</h3>
            <button class="close-menu" id="close-mobile-menu">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="mobile-menu-body">
            <ul class="mobile-menu-links">
                <li><a href="{{ route('frontend_products') }}?sale=1">Today's Deals</a></li>
                <li><a href="{{ route('frontend_products') }}">Customer Service</a></li>
                <li><a href="{{ route('frontend_products') }}">Registry</a></li>
                <li><a href="{{ route('frontend_products') }}">Gift Cards</a></li>
                <li><a href="{{ route('frontend_products') }}">Sell</a></li>
                @if(Auth::check())
                    <li><a href="{{ route('frontend_customer_dashboard') }}">My Account</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                @else
                    <li><a href="{{ route('frontend_customer_login') }}">Sign In</a></li>
                @endif
            </ul>
            
            <!-- Categories in Mobile Menu -->
            <div class="mobile-categories">
                <h4>Categories</h4>
                <ul>
                    @foreach($getProductCats as $category)
                        <li><a href="{{ route('frontend_single_product_category', $category->slug) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
/* Modern E-commerce Header Styles */
.modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.header-top-bar {
    background: rgba(0,0,0,0.1);
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.header-main {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 15px 0;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.header-navigation {
    background: linear-gradient(90deg, #667eea, #764ba2);
    padding: 12px 0;
}

/* Contact Info */
.contact-info {
    display: flex;
    gap: 20px;
    align-items: center;
}

.contact-info span {
    display: flex;
    align-items: center;
    gap: 5px;
    color: rgba(255,255,255,0.9);
}

.contact-info i {
    color: #667eea;
}

/* Top Links */
.top-links {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    align-items: center;
}

.top-links a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: color 0.3s ease;
}

.top-links a:hover {
    color: white;
}

/* Brand Logo */
.brand-logo a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #333;
}

.logo-img {
    height: 40px;
    margin-right: 10px;
    border-radius: 8px;
}

.brand-text {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Search Section */
.search-section {
    width: 100%;
}

.search-form {
    width: 100%;
}

.search-wrapper {
    display: flex;
    width: 100%;
    height: 45px;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    background: white;
}

.category-selector {
    position: relative;
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-width: 140px;
    display: flex;
    align-items: center;
    padding: 0 15px;
    cursor: pointer;
}

.category-selector select {
    border: none;
    background: transparent;
    color: white;
    font-size: 14px;
    font-weight: 500;
    outline: none;
    cursor: pointer;
    appearance: none;
    width: 100%;
}

.category-selector i {
    color: white;
    font-size: 12px;
    margin-left: 8px;
}

.search-input-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    position: relative;
}

.search-input {
    width: 100%;
    height: 100%;
    border: none;
    padding: 0 20px;
    font-size: 16px;
    outline: none;
    color: #333;
}

.search-input::placeholder {
    color: #999;
}

.search-button {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    padding: 0 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    height: 100%;
}

.search-button:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
    transform: scale(1.05);
}

.search-button i {
    font-size: 16px;
}

/* Action Buttons */
.header-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.action-buttons {
    display: flex;
    gap: 15px;
    align-items: center;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border-radius: 25px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

.wishlist-btn {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
}

.wishlist-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
}

.compare-btn {
    background: linear-gradient(135deg, #4ecdc4, #44a08d);
    color: white;
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);
}

.cart-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 12px 20px;
}

.cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.cart-icon-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.cart-info {
    display: flex;
    flex-direction: column;
    margin-left: 8px;
}

.cart-text {
    font-size: 14px;
    font-weight: 600;
}

.cart-total {
    font-size: 12px;
    opacity: 0.8;
}

/* Categories Menu */
.categories-menu {
    position: relative;
}

.categories-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.categories-toggle:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-1px);
}

.categories-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 300px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    padding: 20px;
    z-index: 1000;
    display: none;
    margin-top: 10px;
}

.categories-dropdown.show {
    display: block;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.categories-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.categories-list li {
    margin-bottom: 8px;
}

.categories-list a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.categories-list a:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: translateX(5px);
}

.categories-list i {
    width: 20px;
    text-align: center;
}

/* Main Navigation */
.main-navigation {
    width: 100%;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 30px;
    align-items: center;
    justify-content: flex-end;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 15px;
    border-radius: 20px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}

.nav-link.hot-deals {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    z-index: 1001;
}

.mobile-toggle-btn {
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
}

.mobile-toggle-btn span {
    width: 25px;
    height: 3px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.mobile-toggle-btn.active span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.mobile-toggle-btn.active span:nth-child(2) {
    opacity: 0;
}

.mobile-toggle-btn.active span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Mobile Menu Overlay */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: none;
}

.mobile-menu-content {
    position: absolute;
    top: 0;
    right: 0;
    width: 350px;
    height: 100%;
    background: white;
    color: #333;
    overflow-y: auto;
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

.mobile-menu-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-menu-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.close-menu {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

.close-menu:hover {
    background: rgba(255,255,255,0.2);
}

.mobile-menu-body {
    padding: 20px;
}

.mobile-search {
    margin-bottom: 30px;
}

.mobile-search-wrapper {
    display: flex;
    background: #f8f9fa;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.mobile-search-wrapper input {
    flex: 1;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    outline: none;
    background: transparent;
}

.mobile-search-wrapper button {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    padding: 12px 20px;
    cursor: pointer;
}

.mobile-nav-links {
    list-style: none;
    margin: 0 0 30px 0;
    padding: 0;
}

.mobile-nav-links li {
    margin-bottom: 15px;
}

.mobile-nav-links a {
    color: #333;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    padding: 12px 15px;
    border-radius: 8px;
    display: block;
    transition: all 0.3s ease;
}

.mobile-nav-links a:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: translateX(5px);
}

.mobile-categories {
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.mobile-categories h4 {
    margin-bottom: 15px;
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

.mobile-categories ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-categories li {
    margin-bottom: 10px;
}

.mobile-categories a {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    padding: 8px 15px;
    border-radius: 6px;
    display: block;
    transition: all 0.3s ease;
}

.mobile-categories a:hover {
    background: #f8f9fa;
    color: #333;
}

/* Responsive Design */
@media (max-width: 991px) {
    .header-top-bar {
        display: none;
    }
    
    .header-main .row > div {
        padding: 0 10px;
    }
    
    .action-buttons {
        gap: 10px;
    }
    
    .action-btn {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .action-btn span {
        display: none;
    }
    
    .cart-info {
        display: none;
    }
}

@media (max-width: 768px) {
    .header-navigation {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .search-section {
        margin-bottom: 15px;
    }
    
    .action-buttons {
        justify-content: center;
        gap: 15px;
    }
    
    .action-btn {
        padding: 10px 15px;
    }
    
    .action-btn span {
        display: block;
        font-size: 12px;
    }
}

@media (max-width: 576px) {
    .header-main .row > div {
        padding: 0 5px;
    }
    
    .brand-text {
        font-size: 20px;
    }
    
    .search-wrapper {
        height: 40px;
    }
    
    .category-selector {
        min-width: 120px;
        padding: 0 12px;
    }
    
    .action-buttons {
        gap: 8px;
    }
    
    .action-btn {
        padding: 8px 10px;
    }
    
    .action-btn span {
        font-size: 10px;
    }
}

/* Sticky Header */
.modern-header.sticky {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    animation: slideDown 0.3s ease-out;
}

/* Animation */
@keyframes slideDown {
    from {
        transform: translateY(-100%);
    }
    to {
        transform: translateY(0);
    }
}

/* Cart */
.amazon-cart a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: white;
    padding: 5px;
    border-radius: 3px;
    transition: background-color 0.2s;
}

.amazon-cart a:hover {
    background-color: #37475a;
}

.cart-icon {
    position: relative;
    margin-right: 5px;
}

.cart-icon i {
    font-size: 20px;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff9900;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.cart-text {
    font-size: 14px;
    font-weight: bold;
}

/* Bottom Navigation */
.amazon-nav {
    width: 100%;
}

.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-links li {
    margin-right: 20px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    padding: 5px 0;
    transition: color 0.2s;
}

.nav-links a:hover {
    color: #ff9900;
}

/* Mobile Search */
.mobile-search {
    background: #232f3e;
    padding: 10px 0;
    border-bottom: 1px solid #3a4553;
}

.mobile-search-container {
    display: flex;
    background: white;
    border-radius: 4px;
    overflow: hidden;
}

.mobile-search-container input {
    flex: 1;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    outline: none;
}

.mobile-search-container button {
    background: #ff9900;
    border: none;
    color: white;
    padding: 10px 15px;
    cursor: pointer;
}

/* Mobile Menu */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: none;
}

.mobile-menu-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 300px;
    height: 100%;
    background: white;
    color: #333;
    overflow-y: auto;
}

.mobile-menu-header {
    background: #232f3e;
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-menu-header h3 {
    margin: 0;
    font-size: 18px;
}

.close-menu {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.mobile-menu-body {
    padding: 20px;
}

.mobile-menu-links {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-menu-links li {
    margin-bottom: 15px;
}

.mobile-menu-links a {
    color: #333;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
}

.mobile-categories {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.mobile-categories h4 {
    margin-bottom: 15px;
    color: #333;
    font-size: 16px;
}

.mobile-categories ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-categories li {
    margin-bottom: 10px;
}

.mobile-categories a {
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 991px) {
    .delivery-location,
    .language-selector,
    .account-section,
    .returns-section {
        display: none !important;
    }
    
    .amazon-search {
        display: none;
    }
    
    .mobile-search {
        display: block;
    }
    
    .amazon-header-top .row > div {
        padding: 0 10px;
    }
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }
    
    .hamburger-btn {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 5px;
    }
    
    .hamburger-btn i {
        margin-right: 5px;
        font-size: 16px;
    }
    
    .hamburger-btn span {
        font-size: 14px;
        font-weight: bold;
    }
    
    .amazon-header-bottom {
        padding: 5px 0;
    }
}

@media (max-width: 576px) {
    .amazon-header-top .row > div {
        padding: 0 5px;
    }
    
    .logo-text {
        font-size: 20px;
    }
    
    .cart-text {
        display: none;
    }
    
    .mobile-search-container input {
        font-size: 14px;
        padding: 8px 12px;
    }
    
    .mobile-search-container button {
        padding: 8px 12px;
    }
}


@media (max-width: 576px) {
    .amazon-header-top .row > div {
        padding: 0 5px;
    }
    
    .logo-text {
        font-size: 20px;
    }
    
    .cart-text {
        display: none;
    }
}

/* Sticky Header */
.amazon-header.sticky {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Animation */
@keyframes slideIn {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

.mobile-menu-content {
    animation: slideIn 0.3s ease-out;
}

/* Hamburger Button */
.hamburger-btn {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    border-radius: 3px;
    transition: background-color 0.2s;
}

.hamburger-btn:hover {
    background-color: #37475a;
}

.hamburger-btn i {
    margin-right: 5px;
    font-size: 16px;
}

.hamburger-btn span {
    font-size: 14px;
    font-weight: bold;
}

/* Show hamburger on mobile */
@media (max-width: 768px) {
    .hamburger-btn {
        display: flex;
    }
}

@media (min-width: 769px) {
    .hamburger-btn {
        display: none;
    }
}
</style>

<script>
$(document).ready(function() {
    // Categories dropdown toggle
    $('#categories-toggle').click(function(e) {
        e.stopPropagation();
        $('#categories-dropdown').toggleClass('show');
    });
    
    // Close categories dropdown when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.categories-menu').length) {
            $('#categories-dropdown').removeClass('show');
        }
    });
    
    // Mobile menu toggle
    $('#mobile-toggle').click(function() {
        $(this).toggleClass('active');
        $('#mobile-menu-overlay').fadeToggle(300);
    });
    
    $('#close-mobile-menu').click(function() {
        $('#mobile-toggle').removeClass('active');
        $('#mobile-menu-overlay').fadeOut(300);
    });
    
    // Close mobile menu when clicking overlay
    $('#mobile-menu-overlay').click(function(e) {
        if (e.target === this) {
            $('#mobile-toggle').removeClass('active');
            $(this).fadeOut(300);
        }
    });
    
    // Sticky header
    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
            $('.modern-header').addClass('sticky');
        } else {
            $('.modern-header').removeClass('sticky');
        }
    });
    
    // Search category change
    $('#search-category').change(function() {
        // You can add logic here to filter search results
    });
    
    // Action button hover effects
    $('.action-btn').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});
</script>

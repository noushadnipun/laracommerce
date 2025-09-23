<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products with Images - LaraCommerce</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 40px; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .product-card { background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-image { width: 100%; height: 250px; object-fit: cover; }
        .product-info { padding: 20px; }
        .product-title { font-size: 1.2em; font-weight: bold; margin-bottom: 10px; color: #333; }
        .product-brand { color: #666; font-size: 0.9em; margin-bottom: 10px; }
        .product-description { color: #777; font-size: 0.9em; margin-bottom: 15px; line-height: 1.4; }
        .product-price { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .regular-price { font-size: 1.1em; color: #333; font-weight: bold; }
        .sale-price { font-size: 1.3em; color: #e74c3c; font-weight: bold; }
        .original-price { text-decoration: line-through; color: #999; }
        .product-stock { color: #27ae60; font-weight: bold; }
        .product-code { color: #666; font-size: 0.8em; }
        .gallery-images { display: flex; gap: 5px; margin-top: 10px; }
        .gallery-image { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ Products with Images - LaraCommerce</h1>
            <p>All products now have beautiful images from Unsplash!</p>
        </div>

        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <img src="{{ $product->getFeaturedImageUrl() }}" alt="{{ $product->title }}" class="product-image">
                
                <div class="product-info">
                    <div class="product-title">{{ $product->title }}</div>
                    @if($product->brand)
                        <div class="product-brand">Brand: {{ $product->brand->name }}</div>
                    @endif
                    <div class="product-description">{{ Str::limit($product->description, 100) }}</div>
                    
                    <div class="product-price">
                        @if($product->sale_price && $product->sale_price < $product->regular_price)
                            <span class="sale-price">${{ number_format($product->sale_price / 100, 2) }}</span>
                            <span class="original-price">${{ number_format($product->regular_price / 100, 2) }}</span>
                        @else
                            <span class="regular-price">${{ number_format($product->regular_price / 100, 2) }}</span>
                        @endif
                    </div>
                    
                    <div class="product-stock">Stock: {{ $product->current_stock }} units</div>
                    <div class="product-code">SKU: {{ $product->code }}</div>
                    
                    @if($product->attribute)
                        @php $attributes = json_decode($product->attribute, true); @endphp
                        @if(isset($attributes['gallery']))
                            <div class="gallery-images">
                                @foreach($attributes['gallery'] as $galleryImage)
                                    <img src="{{ $galleryImage }}" alt="Gallery" class="gallery-image">
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>




















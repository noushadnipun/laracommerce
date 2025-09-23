# Helper System Documentation

## Overview
This e-commerce system uses a comprehensive helper system to make the code modular, reusable, and easy to integrate with different templates. All core functionality is abstracted into helper classes that can be easily used across different views and controllers.

## Helper Classes

### 1. Cart Helper (`App\Helpers\Cart\CartHelper`)

**Purpose**: Manages shopping cart functionality

**Key Methods**:
- `addToCart($productId, $quantity, $attributes)` - Add product to cart
- `updateCart($cartKey, $quantity)` - Update cart item quantity
- `removeFromCart($cartKey)` - Remove item from cart
- `getCart()` - Get cart contents
- `getCartCount()` - Get total items in cart
- `getCartTotal()` - Get cart total amount
- `getCartSummary()` - Get complete cart summary
- `clearCart()` - Clear all cart items
- `isEmpty()` - Check if cart is empty
- `validateCart()` - Validate cart items

**Usage Example**:
```php
use App\Helpers\Cart\CartHelper;

// Add to cart
$result = CartHelper::addToCart($productId, 2, ['color' => 'red', 'size' => 'L']);

// Get cart summary
$cart = CartHelper::getCartSummary();
```

### 2. Checkout Helper (`App\Helpers\Checkout\CheckoutHelper`)

**Purpose**: Handles checkout process and order creation

**Key Methods**:
- `processCheckout($request)` - Process complete checkout
- `getCheckoutSummary()` - Get checkout summary with totals
- `validateCheckoutData($request)` - Validate checkout form data
- `getPaymentMethods()` - Get available payment methods
- `calculateShippingCost($request)` - Calculate shipping cost
- `getCouponDiscount()` - Get applied coupon discount

**Usage Example**:
```php
use App\Helpers\Checkout\CheckoutHelper;

// Process checkout
$result = CheckoutHelper::processCheckout($request);

// Get checkout summary
$summary = CheckoutHelper::getCheckoutSummary();
```

### 3. Order Helper (`App\Helpers\Order\OrderHelper`)

**Purpose**: Manages order operations and status updates

**Key Methods**:
- `getOrderStats($dateFrom, $dateTo)` - Get order statistics
- `updateOrderStatus($orderId, $status, $notes, $adminId, $trackingData)` - Update order status
- `getOrderFilters($request)` - Get filtered orders query
- `getOrderTimeline($order)` - Get order status timeline
- `getOrderActions($order)` - Get available order actions
- `canUpdateOrderStatus($currentStatus, $newStatus)` - Check if status can be updated
- `getStatusBadgeClass($status)` - Get CSS class for status badge
- `formatAmount($amount)` - Format currency amount

**Usage Example**:
```php
use App\Helpers\Order\OrderHelper;

// Update order status
$result = OrderHelper::updateOrderStatus($orderId, 'shipped', 'Shipped via DHL', $adminId, [
    'tracking_number' => 'DHL123456',
    'shipping_carrier' => 'DHL'
]);

// Get order statistics
$stats = OrderHelper::getOrderStats();
```

### 4. Product Helper (`App\Helpers\Product\ProductHelper`)

**Purpose**: Handles product-related operations

**Key Methods**:
- `getProductWithDetails($id)` - Get product with all relationships
- `getProductBySlug($slug)` - Get product by slug
- `getFeaturedProducts($limit)` - Get featured products
- `getLatestProducts($limit)` - Get latest products
- `getProductsByCategory($categoryId, $limit)` - Get products by category
- `getProductsByBrand($brandId, $limit)` - Get products by brand
- `searchProducts($query, $limit)` - Search products
- `getProductPrice($product)` - Get formatted product price
- `getStockStatus($product)` - Get stock status
- `getProductImages($product)` - Get product images
- `getProductRating($product)` - Get product rating
- `isInStock($product, $quantity)` - Check stock availability

**Usage Example**:
```php
use App\Helpers\Product\ProductHelper;

// Get product with details
$product = ProductHelper::getProductWithDetails($id);

// Get product price info
$priceInfo = ProductHelper::getProductPrice($product);

// Check stock
$inStock = ProductHelper::isInStock($product, 2);
```

## Blade Components

### 1. Cart Summary Component (`<x-cart.cart-summary>`)

**Purpose**: Displays cart summary with items, totals, and actions

**Parameters**:
- `showItems` (boolean) - Show cart items (default: true)
- `showTotal` (boolean) - Show total amount (default: true)
- `showActions` (boolean) - Show action buttons (default: true)

**Usage**:
```blade
<x-cart.cart-summary :showItems="true" :showTotal="true" :showActions="true" />
```

### 2. Order Status Badge Component (`<x-order.order-status-badge>`)

**Purpose**: Displays order status with appropriate styling

**Parameters**:
- `status` (string) - Order status
- `type` (string) - Badge type: 'order' or 'payment' (default: 'order')
- `size` (string) - Badge size: 'sm', 'md', 'lg' (default: 'sm')

**Usage**:
```blade
<x-order.order-status-badge :status="$order->order_status" />
<x-order.order-status-badge :status="$order->payment_status" type="payment" />
```

## Service Provider

### HelperServiceProvider

**Purpose**: Shares common data across all views

**Shared Variables**:
- `$cartCount` - Total items in cart
- `$cartTotal` - Cart total amount
- `$formattedCartTotal` - Formatted cart total

## Integration with Different Templates

### 1. Using Helpers in Controllers

```php
use App\Helpers\Cart\CartHelper;
use App\Helpers\Product\ProductHelper;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = ProductHelper::getProductBySlug($slug);
        $relatedProducts = ProductHelper::getRelatedProducts($product);
        
        return view('product.show', compact('product', 'relatedProducts'));
    }
}
```

### 2. Using Helpers in Views

```blade
@php
    $cart = \App\Helpers\Cart\CartHelper::getCartSummary();
    $product = \App\Helpers\Product\ProductHelper::getProductWithDetails($id);
@endphp

<div class="cart-count">{{ $cart['count'] }} items</div>
<div class="cart-total">{{ $cart['formatted_total'] }}</div>
```

### 3. Using Components in Views

```blade
<!-- Cart Summary -->
<x-cart.cart-summary />

<!-- Order Status Badge -->
<x-order.order-status-badge :status="'pending'" />
<x-order.order-status-badge :status="'paid'" type="payment" />
```

## Template Integration Steps

### 1. Copy Helper Classes
Copy all helper classes from `app/Helpers/` to your new project.

### 2. Copy Components
Copy all components from `app/View/Components/` and `resources/views/components/`.

### 3. Register Service Provider
Add `HelperServiceProvider` to `config/app.php` providers array.

### 4. Use in Controllers
Import and use helpers in your controllers.

### 5. Use in Views
Use components and helper methods in your views.

## Benefits

1. **Modularity**: Each helper handles specific functionality
2. **Reusability**: Can be used across different templates
3. **Maintainability**: Easy to update and modify
4. **Consistency**: Standardized methods across the application
5. **Testability**: Easy to unit test individual helpers
6. **Documentation**: Well-documented methods and usage

## Best Practices

1. Always use helpers instead of direct model operations
2. Use components for reusable UI elements
3. Keep helpers focused on single responsibility
4. Use type hints and return types
5. Handle errors gracefully
6. Document all public methods
7. Use consistent naming conventions

## Error Handling

All helpers return standardized response arrays:
```php
// Success response
['success' => true, 'message' => 'Operation successful', 'data' => $data]

// Error response
['success' => false, 'message' => 'Error message', 'errors' => $errors]
```

This makes it easy to handle responses consistently across the application.






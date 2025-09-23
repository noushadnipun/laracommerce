<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class MiniCartComponent extends Component
{
    public $cartItems;
    public $totalItems;
    public $totalPrice;
    public $isEmpty;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->loadCartData();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.mini-cart-component');
    }

    /**
     * Load cart data for mini cart
     */
    private function loadCartData()
    {
        $cart = Session::get('cart', []);
        $this->cartItems = [];
        $this->totalItems = 0;
        $this->totalPrice = 0;
        $this->isEmpty = empty($cart);

        if (!$this->isEmpty) {
            $count = 0;
            foreach ($cart as $id => $details) {
                if ($count >= 3) break; // Show only first 3 items
                
                $product = Product::find($details['id']);
                
                if ($product) {
                    $itemTotal = $details['price'] * $details['qty'];
                    
                    $this->cartItems[] = [
                        'id' => $id,
                        'product_id' => $details['id'],
                        'name' => $details['name'],
                        'price' => $details['price'],
                        'quantity' => $details['qty'],
                        'total' => $itemTotal,
                        'image' => $details['image'] ?? '/images/no-image.jpg',
                        'slug' => $product->slug ?? '#'
                    ];
                    
                    $this->totalItems += $details['qty'];
                    $this->totalPrice += $itemTotal;
                    $count++;
                }
            }
        }
    }
}
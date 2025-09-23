<?php

namespace App\View\Components\Cart;

use Illuminate\View\Component;
use App\Helpers\Cart\CartHelper;

class CartSummary extends Component
{
    public $cart;
    public $showItems;
    public $showTotal;
    public $showActions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($showItems = true, $showTotal = true, $showActions = true)
    {
        $this->cart = CartHelper::getCartSummary();
        $this->showItems = $showItems;
        $this->showTotal = $showTotal;
        $this->showActions = $showActions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cart.cart-summary');
    }
}
<?php

namespace App\View\Components\Order;

use Illuminate\View\Component;
use App\Helpers\Order\OrderHelper;

class OrderStatusBadge extends Component
{
    public $status;
    public $type;
    public $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($status, $type = 'order', $size = 'sm')
    {
        $this->status = $status;
        $this->type = $type;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.order.order-status-badge');
    }

    public function getBadgeClass()
    {
        if ($this->type === 'payment') {
            return OrderHelper::getPaymentBadgeClass($this->status);
        }
        
        return OrderHelper::getStatusBadgeClass($this->status);
    }

    public function getStatusLabel()
    {
        if ($this->type === 'payment') {
            $labels = OrderHelper::getPaymentStatusOptions();
            return $labels[$this->status] ?? $this->status;
        }
        
        $labels = OrderHelper::getOrderStatusOptions();
        return $labels[$this->status] ?? $this->status;
    }
}
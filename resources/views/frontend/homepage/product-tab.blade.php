<div class="product_area mb-46">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="product_tab_btn3">
                        <ul class="nav" role="tablist">
                            <li>
                                <a class="active" data-toggle="tab" href="#Products3" role="tab" aria-controls="Products3" aria-selected="false">
                                    New Products
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#Sale3" role="tab" aria-controls="Sale3" aria-selected="false">
                                    Sale Products
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> 
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Products3" role="tabpanel">
                    <div class="product_carousel product_column5 owl-carousel">

                       @php 
                       $queryNewProduct = \App\Models\Product::where('visibility', '1')
                                        ->orderBy('created_at', 'DESC')
                                        ->limit('20')->get();

                        echo \App\Helpers\Frontend\ProductView::view($queryNewProduct); 
                       @endphp

                    </div>     
                </div>
                <div class="tab-pane fade" id="Sale3" role="tabpanel">
                    <div class="product_carousel product_column5 owl-carousel">
                       @php 
                       $querySaleProduct = \App\Models\Product::where('visibility', '1')
                                    ->whereNotNull('sale_price')
                                    ->orderBy('created_at', 'DESC')
                                    ->limit('20')->get();
                        echo \App\Helpers\Frontend\ProductView::view($querySaleProduct); 
                       @endphp
                    </div>  
                </div>  
            </div>   
        </div>
    </div>
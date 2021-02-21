@extends('admin.layouts.master')
@section('site-title')
    Store Settings
@endsection

@section('page-content')
<div class="row">
    <?php function storeSetting($arg){
        $get = \App\Models\StoreSettings::where('meta_name', $arg)->first();
        return $get->meta_value;
    }?>
    <div class="col-md-6">
        <form action="{{route('admin_product_store_settings_update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Shipping Setting -->
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">
                        Shipping Settings
                    </h3>
                </div><!-- end card-header-->

                <div class="div card-body">

                    <div class="form-group"> <!-- Shipping Type -->
                        <label for="">Shippig Type</label>
                        <input name="meta_name[]" type="hidden" value="shipping_type">
                        <select class="form-control form-control-sm" name="shipping_type">
                            <option value="flat_rate" {{storeSetting('shipping_type') == 'flat_rate' ? 'selected' : ''}}> Flat Rate </option>
                            <option value="product_base" {{storeSetting('shipping_type') == 'product_base' ? 'selected' : ''}}> Product Base </option>
                        </select>
                    </div><!-- End Shipping Type -->

                    <div class="form-group"><!-- Shipping Flat Rate-->
                        <label for="">Shippig Flat Rate</label>
                        <input name="meta_name[]" type="hidden" value="shipping_flat_rate">
                        <input name="shipping_flat_rate" type="text" class="form-control form-control-sm" value="{{storeSetting('shipping_flat_rate') }}">
                    </div><!-- End Shipping Flat Rate -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>              
            </div>

            <!-- SSL Commerze Setting -->
            <div class="card">
                <div class="card-header card-info">
                    <h3 class="card-title panel-title float-left">
                        Ssl Commerze Settings
                    </h3>
                </div><!-- end card-header-->

                <div class="div card-body">

                    <div class="form-group"> <!-- SSL Sandbox /Live Type -->
                        <label for="">Use</label>
                        <input name="meta_name[]" type="hidden" value="ssl_sandbox_live">
                        <select class="form-control form-control-sm" name="ssl_sandbox_live">
                            <option value="live" {{storeSetting('ssl_sandbox_live') == 'live' ? 'selected' : ''}}> Live </option>
                            <option value="sandbox" {{storeSetting('ssl_sandbox_live') == 'sandbox' ? 'selected' : ''}}>  Sandbox </option>
                        </select>
                    </div><!-- End SSL Sandbox /Live Type -->

                    <div class="form-group"><!-- Store ID -->
                        <label for="">Store ID</label>
                        <input name="meta_name[]" type="hidden" value="ssl_store_id">
                        <input name="ssl_store_id" type="text" class="form-control form-control-sm" value="{{storeSetting('ssl_store_id') }}" autocomplete="off">
                    </div><!-- End Store ID -->

                    <div class="form-group"><!-- Store Passowrd -->
                        <label for="">Store Passowrd</label>
                        <input name="meta_name[]" type="hidden" value="ssl_store_password">
                        <input name="ssl_store_password" type="text" class="form-control form-control-sm" value="{{storeSetting('ssl_store_password') }}" autocomplete="off">
                    </div><!-- End Store Password -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>              
            </div>

            <!-- End -->
        </form>
    </div>
</div>
@endsection
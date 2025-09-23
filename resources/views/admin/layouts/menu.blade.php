@extends('admin.layouts.master')
@section('title','Dashboard')
@push('css')
@endpush
@section('main_menu','HOME')
@section('active_menu','Dashboard')
@section('link',route('admin_dashboard'))
@section('page-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Admin Menu</h3>
                    </div>
                    <div class="card-body">
                        {!! $menu !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('cusjs')
    {!! $mscript !!}
@endsection
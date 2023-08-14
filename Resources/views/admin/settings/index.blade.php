@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/shop.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    @include('product::admin.settings.breadcrumbs')
    @include('admin.notify')

    <div class="row">
        <div class="col-xs-12">
            <h3>@lang('product::admin.product_settings.index')</h3><br>
            <div class="settings-icons-wrapper">
                <div>
                    <a href="{{ route('admin.measuring-units.index') }}">
                        <i class="fas fa-balance-scale-right fa-5x"></i>
                        <span>{{ __('product::admin.measure_units.index') }}</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

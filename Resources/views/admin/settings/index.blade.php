@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/shop.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    @include('shop::admin.settings.main.breadcrumbs')
    @include('admin.notify')

    <div class="row">
        <div class="col-xs-12">
            <h3>@lang('shop::admin.shop.settings_index')</h3><br>
            <div class="alert alert-warning">{!! __('shop::admin.main_settings.warning') !!}</div>
            <div class="settings-icons-wrapper">
                <div>
                    <a href="{{ route('admin.shop.settings.main.index') }}">
                        <img src="{{ asset('admin/assets/images/cart.svg') }}">
                        <span>{{ __('shop::admin.main_settings.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('payments.index') }}">
                        <img src="{{ asset('admin/assets/images/cart.svg') }}">
                        <span>{{ __('shop::admin.payments.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('deliveries.index') }}">
                        <i class="fas fa-truck fa-5x"></i>
                        <span>{{ __('shop::admin.deliveries.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('zip_codes.index') }}">
                        <img src="{{ asset('admin/assets/images/cart.svg') }}">
                        <span>{{ __('shop::admin.post_codes.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('vats.countries.index') }}">
                        <img src="{{ asset('admin/assets/images/cart.svg') }}">
                        <span>{{ __('shop::admin.vats.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('admin.currencies.index') }}" class="text-center">
                        <i class="fas fa-pound-sign fa-5x"></i>
                        <span>{{ __('shop::admin.currencies.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('admin.measuring-units.index') }}">
                        <i class="fas fa-balance-scale-right fa-5x"></i>
                        <span>{{ __('shop::admin.measure_units.index') }}</span>
                    </a>
                </div>

                <div>
                    <a href="{{ route('admin.shop.settings.internal-integrations.index') }}">
                        <i class="fas fa-rocket fa-5x"></i>
                        <span>{{ __('shop::admin.main_settings.integrations') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

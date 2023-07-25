@extends('layouts.front.app')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/front/plugins/cubeportfolio/css/cubeportfolio.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/front/plugins/cubeportfolio/css/remove_padding.css') }}">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('/front/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/front/plugins/cubeportfolio/js/main.js') }}"></script>
@endsection
@section('content')
    @include('front.partials.breadcrumbs')
    @php
        $product = $viewArray['currentModel']->parent;
        $category = $viewArray['currentModel']->parent->category;
    @endphp

    <div class="page-product">
        <div class="shell">
            <div class="page-prod-head">
                <div class="label" data-aos="fade-up" data-aos-delay="50"></div>

                <h3 data-aos="fade-up" data-aos-delay="100">{{ $category->title }}</h3>
            </div>
        </div>

        {{--      Icons here --}}
        <div class="shell">
            <div class="prod-elements">
                <div class="prod-image-wrapper" data-aos="fade-up" data-aos-delay="50">
                    <a href="#"></a>

                    <div class="prod-image parent-image-wrapper">
                        <img src="{{ $product->getFileUrl() }}" alt="" class="bg-image">
                    </div>
                </div>

                <div class="prod-content">
                    <h3 data-aos="fade-up" data-aos-delay="50">{{ $viewArray['currentModel']->title }}</h3>

                    <div class="prod-info">
                        <p data-aos="fade-up" data-aos-delay="50">
                            <span>{{ __('shop::front.product.category') }}:</span>{{ $category->title }} </p>

                        <p data-aos="fade-up" data-aos-delay="100">
                            <span>{{ __('shop::front.product.weight') }}:</span>{{ $product->weight }} </p>
                    </div>

                    <div class="page-price" data-aos="fade-up" data-aos-delay="150">
                        {{--                        <div class="price price-old">--}}
                        {{--                            {{ __('front.from') }} <strong>98.00</strong> <span>{{ __('front.currency') }}</span>--}}
                        {{--                        </div>--}}

                        <div class="price">
                            {{--                            {{ __('front.from') }} --}}

                            <strong>{{ $product->getPrice() }}</strong> <span>{{ __('front.currency') }}</span>
                        </div>
                    </div>

                    <form action="">
                        <button class="btn btn-prod" type="submit" data-aos="fade-up" data-aos-delay="200">
                            <span>{{ __('front.inquiry') }}</span>

                            <i class="arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>

            <p data-aos="fade-up" data-aos-delay="50">
                {!! $viewArray['currentModel']->description !!}

                @include('front.partials.content.after_description_modules', ['model' => $viewArray['currentModel']])
            </p>

            @include('shop::front.products.additional_fields')
        </div>
    </div>

    <div class="page-content">
        <div class="shell">
            <div class="article">
                @include('front.partials.content.additional_titles_and_texts', ['model' => $viewArray['currentModel']])
            </div>

            <div class="page-bottom">
                <div class="page-price" data-aos="fade-up" data-aos-delay="100">
                    <div class="price">
                        <strong>{{ $product->getPrice() }}</strong> <span>{{ __('front.currency') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-gallery" data-aos="fade-up" data-aos-delay="100">
        @include('front.partials.content.inner_gallery')
    </div>

    @if(!is_null($product->getPreviousProductUrl($languageSlug)) || !is_null($product->getNextProductUrl($languageSlug)))
        <div class="page-nav">
            @if(!is_null($product->getPreviousProductUrl($languageSlug)))
                <a href="{{ $product->getPreviousProductUrl($languageSlug) }}" data-aos="fade-up" data-aos-delay="100" class="page-prev">
                    <span>{{ __('shop::front.product.previous') }}</span>
                </a>
            @endif

            @if(!is_null($product->getNextProductUrl($languageSlug)))
                <a href="{{ $product->getNextProductUrl($languageSlug) }}" data-aos="fade-up" data-aos-delay="100" class="page-next">
                    <span>{{ __('shop::front.product.next') }}</span>
                </a>
            @endif
        </div>
    @endif
@endsection

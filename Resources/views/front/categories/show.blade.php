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
    @include('front.partials.inner_header')
    @include('front.partials.breadcrumbs')

    @php
        $category = $viewArray['currentModel']->parent;
        $subCategories = $category->subCategories;
    @endphp

    <section class="section-top section-top-2">
        <div class="shell">
            <img src="{{ asset('front/assets/icons/attachment-6.svg') }}" alt="" data-aos="fade-up" data-aos-delay="50">

            <div class="label" data-aos="fade-up" data-aos-delay="50"></div>

            <div class="section-content">
                <h3 data-aos="fade-up" data-aos-delay="100">{{ $viewArray['currentModel']->title }}<span class="color-red">:</span></h3>

                <p data-aos="fade-up" data-aos-delay="150">{!! $viewArray['currentModel']->announce !!}</p>
            </div>
        </div>
    </section>

    <ul class="list-icons">
        @foreach($subCategories as $subCategory)
            <li @if(url()->current() === $subCategory->getUrl($languageSlug)) class="active" @endif>
                <a href="{{ $subCategory->getUrl($languageSlug) }}"></a>
                <img src="{{ $subCategory->getFileUrl() }}" alt="">
                <p>{{ $subCategory->title }}</p>
            </li>
        @endforeach
    </ul>

    @if($viewArray['currentModel']->parent->getActiveProducts->isNotEmpty())
        @include('product::front.categories.list_products', ['products' => $viewArray['currentModel']->parent->getActiveProducts])
    @endif

    @foreach($subCategories as $subCategory)
        @if($subCategory->getActiveProducts->isNotEmpty())
            @include('product::front.categories.list_products', ['products' => $subCategory->getActiveProducts])
        @endif
    @endforeach

    <section class="section-text section-text-alt section-text-3">
        <div class="shell">
            <div class="section-content" data-aos="fade-up" data-aos-delay="50">
                <p>{!! $viewArray['currentModel']->description !!}</p>
            </div>
        </div>
    </section>
@endsection

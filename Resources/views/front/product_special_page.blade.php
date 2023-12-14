@extends('layouts.front.app')

@section('content')
    @include('front.partials.inner_header')
    @include('front.partials.breadcrumbs')

    <section class="section-top">
        <div class="shell">
            <div class="section-content">
                <h3 class="ad-box-type-color-5" data-aos="fade-up" data-aos-delay="100">{{ $viewArray['currentModel']->title }}</h3>

                <p data-aos="fade-up" data-aos-delay="150">{!! $viewArray['currentModel']->announce !!}</p>
            </div>
        </div>
    </section>

    <div class="brands">
        <div class="brand" data-aos="fade-up">
            @foreach($brands as $brand)
                <div class="brand-img">
                    <a href="{{ $brand->getUrl($languageSlug) }}"></a>

                    <img src="{{ $brand->getFileUrl() }}" alt="{{ $brand->title }}">
                </div>

                <h3>
                    <a href="{{ $brand->getUrl($languageSlug) }}">{{ $brand->title }}</a>
                </h3>
        </div>
        @endforeach
    </div>

    @if($viewArray['currentModel']->description != '')
        <section class="section-text">
            <div class="shell">
                <div class="section-content" data-aos="fade-up" data-aos-delay="150">
                    <p>{!! $viewArray['currentModel']->description !!}</p>
                </div>
            </div>
        </section>
    @endif

@endsection

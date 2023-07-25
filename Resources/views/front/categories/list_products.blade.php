<div class="boxes-type-5">
    @foreach($products as $product)
        <div class="box" data-aos="fade-up">
            <div class="box-image-wrapper">
                    <div class="labels">
                        @if($product->isNewProduct())
                            <div class="label label-new">{{ __('shop::front.product.label_new') }}</div>
                        @endif

                        @if($product->isPromoProduct())
                            <div class="label">{{ __('shop::front.product.label_promo') }}</div>
                        @endif
                    </div>

                <a href="{{ $product->getUrl($languageSlug) }}"></a>

                <div class="box-image-inner">
                    <div class="box-image parent-image-wrapper">
                        <img src="{{ $product->getFileUrl() }}" alt="{{ $product->title }}" class="bg-image">
                    </div>
                </div>
            </div>

            <div class="box-content">
                <h3>
                    <a href="{{ $product->getUrl($languageSlug) }}">{{ $product->title }}</a>
                </h3>

                <p>{!! $product->announce !!}</p>

                <div class="box-actions">
                    <div class="box-prices">
                        <p class="old-price">
                            <span>{{ __('front.from') }}</span>

                            <strong>118.00
                                <span>{{ __('front.currency') }}</span>
                            </strong>
                        </p>

                        <p>
                            <span>{{ __('front.from') }}</span>

                            <strong>96.00
                                <span>{{ __('front.currency') }}</span>
                            </strong>
                        </p>
                    </div>

                    <a href="{{ $product->getUrl($languageSlug) }}" class="link-more color-red">...{{ __('front.see_more') }}</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="boxes-type-5">
    @foreach($products as $product)
        <div class="box" data-aos="fade-up">
            <div class="box-image-wrapper">
                <div class="labels">
                    @if($product->isNewProduct())
                        <div class="label label-new">{{ __('product::front.product.label_new') }}</div>
                    @endif

                    @if($product->isPromoProduct())
                        <div class="label">{{ __('product::front.product.label_promo') }}</div>
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
                    <p class="box-prices">
                    @if(!empty($product->getCatalogDiscountedPrice()))
                        <p class="old-price">
                            @if($product->catalog_from_discounted_price)
                                <span>{{ __('front.from') }}</span>
                            @endif
                            <strong>{{ $product->getCatalogDiscountedPrice() }}</strong> <span>{{ __('front.currency') }}</span>
                        </p>
                        <p>
                            @if($product->catalog_from_price)
                                <span>{{ __('front.from') }}</span>
                            @endif

                            <strong>{{ $product->getPrice() }}</strong> <span>{{ __('front.currency') }}</span>
                        </p>
                    @else
                        <p>
                            @if($product->catalog_from_price)
                                <span>{{ __('front.from') }}</span>
                            @endif

                            <strong>{{ $product->getPrice() }}</strong> <span>{{ __('front.currency') }}</span>
                        </p>
                    @endif
                </div>

                <a href="{{ $product->getUrl($languageSlug) }}" class="link-more color-red">...{{ __('front.see_more') }}</a>
            </div>
        </div>
</div>@endforeach</div>

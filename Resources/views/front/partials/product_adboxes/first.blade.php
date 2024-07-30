@php use Carbon\Carbon; @endphp@php use App\Models\AdBoxButton; @endphp
<section class="section">
    <div class="section-head">
        <div class="shell">
            <h3 class="section-title">{{ __('messages.trainings') }}</h3>

            <img src="{{ asset('website/assets/icons/shape-1.svg') }}" alt="">
        </div>
    </div>

    <div class="boxes-type-1">
        @foreach($productAdBoxesFirstType as $adBox)
            @php
                $adBoxTranslation = $adBox->currentTranslation;
                if(is_null($adBoxTranslation)){
                    continue;
                }
                $startEventDate = Carbon::parse($adBox->product->start_event);
                $endEventDate = Carbon::parse($adBox->product->end_event);
                $endPromoDate = ($adBox->product->early_booking_days) ? Carbon::parse($adBox->product->start_event)->subDays($adBox->product->early_booking_days+1) : $now->subDays(100);
            @endphp
            <div class="box" data-aos="fade-up">
                <div class="box-image-wrapper">
                    @if($adBox->product->is_new_product)
                        <div class="label">{{ __('messages.new') }}</div>
                    @endif

                    @if($adBox->product->is_in_promotion)
                        <div class="label label-promo">{{ __('messages.promo') }}</div>
                    @endif

                    <a href="{{ $adBox->product->getProductUrl($language) }}"></a>

                    <div class="box-image parent-image-wrapper">
                        <img src="{{ $adBox->product->fullImageFilePathUrl() }}" alt="{{ $adBoxTranslation->title }}" class="bg-image">
                    </div>
                </div>

                <p class="date">{{ $startEventDate->format('d.m.Y') }} - {{ $endEventDate->format('d.m.Y') }}</p>

                <div class="box-content">
                    <h3>
                        <a href="{{ $adBox->product->getProductUrl($language) }}">{{ $adBoxTranslation->title }}</a>
                    </h3>

                    <p>{!! \Illuminate\Support\Str::limit($adBoxTranslation->short_description, 255, ' ...') !!}</p>

                    <div class="box-info">
                        @if(! is_null($adBox->product->price) && $adBox->product->price > 0)
                            <div class="prices">
                                @if($adBox->product->discount_price > 0 && $now->diffInDays($endPromoDate, false) >= 0 && $now->diffInDays($startEventDate, false) >= 0)
                                    <p class="old-price">{!! trans('messages.price') !!} <span>{{ $adBox->product->formatedPrice($adBox->product->price) }} {!! trans('messages.bgn') !!}</span>.</p>

                                    {{--                                    <p class="text-black">{!! trans('messages.price') !!} <span>{{ $adBox->product->formatedPrice($adBox->product->discount_price) }} {!! trans('messages.bgn') !!}</span></p>--}}
                                @else
                                    <p class="text-black">{!! trans('messages.price') !!} <span>{{ $adBox->product->formatedPrice($adBox->product->price) }} {!! trans('messages.bgn') !!}</span></p>
                                @endif
                            </div>
                        @endif

                        @if($now->diffInDays($endPromoDate, false) >= 0)
                            <div class="promo">
                                <p class="text-warning">{{ __('messages.price_early_book_seat') }} <span>{{ $adBox->product->formatedPrice($adBox->product->discount_price) }} {!! trans('messages.bgn') !!}</span></p>

                                <p>{{ __('messages.remaining') }}: <span>{{ $now->diffInDays($endPromoDate, false) }} {{ __('messages.days') }}</span></p>
                            </div>
                        @endif

                        <div class="total-places">
                            <p class="text-warning">{{ __('messages.count_seats') }}: <span>{{ $adBox->product->seats }}</span></p>

                            <p class="text-success">{{ __('messages.free_seats') }}: <span>{{ $adBox->product->units_in_stock }}</span></p>
                        </div>
                    </div>
                    <a href="{{ $adBox->product->getProductUrl($language) }}" class="btn btn-main">{{ ($adBox->product->units_in_stock && $now->diffInDays($startEventDate, false) >= 0)  ? __('messages.book_seat') : __('messages.no_free_seats') }}</a>
                </div>
            </div>
        @endforeach
    </div>

    {{--    @php--}}
    {{--        $adBoxButton = AdBoxButton::getTranslation(1, $language->id);--}}
    {{--    @endphp--}}
    {{--    @if($adBoxButton && $adBoxButton->url)--}}
    {{--        <div class="section-actions">--}}
    {{--            <a href="{{ (!is_null($adBoxButton)) ? ($adBoxButton->external_url) ? $adBoxButton->url : url($adBoxButton->url) :''}}" class="btn btn-gray btn-big">{!! $adBoxButton->title !!}</a>--}}
    {{--        </div>--}}
    {{--    @endif--}}

</section>


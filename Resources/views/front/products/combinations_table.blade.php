@if($viewArray['currentModel']->parent->combinations->isNotEmpty())
    <div class="product-table" data-aos="fade-up" data-aos-delay="100">
        <h3>{{ __('front.parameters') }}</h3>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>{{ __('front.pr_attribute_code') }}</th>
                    @foreach($category->productAttributes as $productAttribute)
                        <th>{{ $productAttribute->title }}</th>
                    @endforeach
                    <th>{{ __('front.pr_attribute_price') }}</th>
                    <th>{{ __('front.pr_attribute_availability') }}</th>
                </tr>
                </thead>

                <tbody>
                @foreach($viewArray['currentModel']->parent->combinations as $productCombination)
                    @php
                        $combinationArray = $productCombination->filter_combo;
                    @endphp
                    <tr>
                        <td>{{ $productCombination->sku }}</td>
                        @foreach($category->productAttributes as $productAttribute)
                            @foreach($productAttribute->values as $productAttributeValue)
                                @if(in_array($productAttributeValue->id, $combinationArray))
                                    <td>{{ $productAttributeValue->title }}</td>
                                @endif
                            @endforeach
                        @endforeach
                        <td>{{ $productCombination->price }} {{ __('front.currency') }}</td>
                        <td>{{ trans('front.stock_status_' . $productCombination->stock_status) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

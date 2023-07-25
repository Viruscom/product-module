<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product-attribute.values.index', ['id' => $productAttribute->id]) }}" class="text-black">{{ __('product::admin.product_attribute_values.index') }}</a>
        </li>
        @if(url()->current() === route('admin.product-attribute.values.create', ['id' => $productAttribute->id]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-attribute.values.create', ['id' => $productAttribute->id]) }}" class="text-purple">{{ __('product::admin.product_attribute_values.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.product-attribute.values.edit', ['id' => $productAttribute->id, 'value_id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-attributes.edit', ['id' => $productAttribute->id, 'value_id' => Request::segment(4)]) }}" class="text-purple">{{ __('product::admin.product_attribute_values.edit') }}</a>
            </li>
        @endif
    </ul>
</div>

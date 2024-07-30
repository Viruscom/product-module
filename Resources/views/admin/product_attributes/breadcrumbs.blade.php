<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product-attributes.index') }}" class="text-black">{{ __('product::admin.product_attributes.index') }}</a>
        </li>
        @if(url()->current() === route('admin.product-attributes.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-attributes.create') }}" class="text-purple">{{ __('product::admin.product_attributes.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.product-attributes.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-attributes.edit', ['id' => Request::segment(4)]) }}" class="text-purple">{{ __('product::admin.product_attributes.edit') }}</a>
            </li>
        @endif
    </ul>
</div>

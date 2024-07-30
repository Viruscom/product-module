<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product-categories.index') }}" class="text-black">{{ __('product::admin.product_categories.index') }}</a>
        </li>
        @if(url()->current() === route('admin.product-categories.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-categories.create') }}" class="text-purple">{{ __('product::admin.product_categories.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.product-categories.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-categories.edit', ['id' => Request::segment(4)]) }}" class="text-purple">{{ __('product::admin.product_categories.edit') }}</a>
            </li>
        @endif
    </ul>
</div>

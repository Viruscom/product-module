<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.brands.index') }}" class="text-black">{{ __('product::admin.product_brands.index') }}</a>
        </li>
        @if(url()->current() === route('admin.brands.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.brands.create') }}" class="text-purple">{{ __('product::admin.product_brands.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.brands.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.brands.edit', ['id' => Request::segment(4)]) }}" class="text-purple">{{ __('product::admin.product_brands.edit') }}</a>
            </li>
        @endif
    </ul>
</div>

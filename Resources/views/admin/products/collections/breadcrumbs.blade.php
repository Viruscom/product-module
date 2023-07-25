<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product-collections.index') }}" class="text-black">@lang('shop::admin.product_collections.index')</a>
        </li>
        @if(url()->current() === route('admin.product-collections.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-collections.create') }}" class="text-purple">@lang('shop::admin.product_collections.create')</a>
            </li>
        @elseif(!is_null(Request::segment(4)) && url()->current() === route('admin.product-collections.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-collections.edit', ['id' => Request::segment(4)]) }}" class="text-purple">@lang('shop::admin.product_collections.edit')</a>
            </li>
        @endif
    </ul>
</div>


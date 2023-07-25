<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product-stocks.internal-suppliers.index') }}" class="text-black">@lang('shop::admin.internal_suppliers.index')</a>
        </li>
        @if(url()->current() === route('admin.product-stocks.internal-suppliers.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-stocks.internal-suppliers.create') }}" class="text-purple">{{ __('shop::admin.internal_suppliers.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.product-stocks.internal-suppliers.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-stocks.internal-suppliers.edit', ['id' => Request::segment(4)]) }}" class="text-purple">@lang('shop::admin.internal_suppliers.edit')</a>
            </li>
        @elseif(url()->current() === route('admin.product-stocks.internal-suppliers.archived'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product-stocks.internal-suppliers.archived') }}" class="text-purple">@lang('shop::admin.internal_suppliers.index_archived')</a>
            </li>
        @endif
    </ul>
</div>

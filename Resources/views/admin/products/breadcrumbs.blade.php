<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="text-black">{{ __('product::admin.products.index') }}</a>
        </li>

        @if(Request::segment(5) !== null && url()->current() === route('admin.products.create', ['category_id' => Request::segment(5)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.products.create', ['category_id' => Request::segment(5)]) }}" class="text-purple">{{ __('product::admin.products.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.products.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.products.edit', ['id' => Request::segment(4)]) }}" class="text-purple">{{ __('product::admin.products.edit') }}</a>
            </li>
        @endif
    </ul>
</div>

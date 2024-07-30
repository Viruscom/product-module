<div class="breadcrumbs">
    <ul>
        @if(url()->current() === route('admin.product.index'))
            <li>
                <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{ route('admin.product.index') }}" class="text-black">@lang('admin.product.index')</a>
            </li>
        @elseif(url()->current() === route('admin.banners.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product.create') }}" class="text-purple">@lang('admin.product.create')</a>
            </li>
        @elseif(url()->current() === route('admin.product.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.product.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('admin.product.edit')</a>
            </li>
        @endif
    </ul>
</div>


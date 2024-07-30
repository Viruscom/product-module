<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.product.settings.index') }}" class="text-black">{{ __('product::admin.product_settings.index') }}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.measuring-units.index') }}" class="text-black">{{ __('product::admin.measure_units.index') }}</a>
        </li>
        @if(url()->current() === route('admin.measuring-units.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.measuring-units.create') }}" class="text-purple">{{ __('product::admin.measure_units.create') }}</a>
            </li>
        @elseif(Request::segment(5) != null && url()->current() === route('admin.measuring-units.edit', ['id' => Request::segment(5)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.measuring-units.edit', ['id' => Request::segment(5)]) }}" class="text-purple">{{ __('product::admin.measure_units.edit') }}</a>
            </li>
        @endif
    </ul>
</div>


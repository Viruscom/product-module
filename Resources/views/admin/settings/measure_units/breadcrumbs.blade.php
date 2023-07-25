<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.shop.settings.index') }}" class="text-black">{{ __('shop::admin.main_settings.index') }}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.measuring-units.index') }}" class="text-black">{{ __('shop::admin.measure_units.index') }}</a>
        </li>
        @if(url()->current() === route('admin.measuring-units.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.measuring-units.create') }}" class="text-purple">{{ __('shop::admin.measure_units.create') }}</a>
            </li>
        @elseif(Request::segment(4) !== null && url()->current() === route('admin.measuring-units.edit', ['id' => Request::segment(4)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.measuring-units.edit', ['id' => Request::segment(4)]) }}" class="text-purple">{{ __('shop::admin.measure_units.edit') }}</a>
            </li>
        @endif
    </ul>
</div>


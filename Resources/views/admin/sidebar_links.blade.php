<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFourProduct" data-toggle="collapse" data-parent="#accordionMenu" href="#collapseFourProduct" aria-expanded="false" aria-controls="collapseThree">
        <h4 class="panel-title">
            <a>
                <i class="far fa-list-alt"></i> <span>{!! trans('product::admin.products.index') !!}</span>
            </a>
        </h4>
    </div>
    <div id="collapseFourProduct" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFourProduct">
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{ route('admin.brands.index') }}"><i class="fas fa-copyright"></i> <span>{!! trans('product::admin.product_brands.index') !!}</span></a></li>
                <li><a href="{{ route('admin.product-categories.index') }}"><i class="fas fa-outdent"></i> <span>{!! trans('product::admin.product_categories.index') !!}</span></a></li>
                <li><a href="{{ route('admin.products.index') }}"><i class="far fa-list-alt"></i> <span>{!! trans('product::admin.products.index') !!}</span></a></li>
                <li><a href="{{ route('admin.product-attributes.index') }}"><img src="{{ asset('admin/assets/images/product_attribute.svg') }}" alt="@lang('product::admin.product_attributes.index')" width="18" style="margin-right: 12px;"> <span>{!! trans('product::admin.product_attributes.index') !!}</span></a></li>
                <li><a href="{{ route('admin.products.characteristics.index') }}"><img src="{{ asset('admin/assets/images/product_characteristics.svg') }}" alt="@lang('product::admin.product_characteristics.index')" width="18" style="margin-right: 12px;"> <span>{!! trans('product::admin.product_characteristics.index') !!}</span></a></li>
                <li><a href="{{ route('admin.product-combinations.index') }}"><img src="{{ asset('admin/assets/images/product_combinations.svg') }}" alt="@lang('product::admin.product_combinations.index')" width="18" style="margin-right: 12px;"> <span>{!! trans('product::admin.product_combinations.index') !!}</span></a></li>
                <li><a href="{{ route('admin.product.settings.index') }}"><i class="fas fa-cogs"></i> <span>{!! trans('product::admin.product.settings_index') !!}</span></a></li>
            </ul>
        </div>
    </div>
</div>

@extends('layouts.admin.app')

@section('content')
    @include('product::admin.products.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.modals.delete_confirm')
    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            <div class="checkbox-all pull-left p-10 p-l-0">
                <div class="pretty p-default p-square">
                    <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.mark_demark_all_elements') }}" data-trigger="hover"/>
                    <div class="state p-primary">
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="collapse-buttons pull-left p-7">
                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.expand_all_marked_elements') }}"></i></a>
                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.collapse_all_marked_elements') }}"></i></a>
            </div>
            <div class="search pull-left hidden-xs">
                <div class="input-group">
                    <input type="text" name="search" class="form-control input-sm search-text" placeholder="{{ __('admin.common.search') }}">
                    <span class="input-group-btn">
					<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
				</span>
                </div>
            </div>

            <div class="action-mass-buttons pull-right">
                <a href="{{ route('admin.products.create', ['category_id' => Request::segment(5)]) }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.create_new') }}">
                    <i class="fas fa-plus"></i>
                </a>

                <a href="{{ route('admin.products.active-multiple', ['active' => 0]) }}" class="btn btn-lg tooltips light-grey-eye mass-unvisible" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.deactivate_all_marked_elements') }}">
                    <i class="far fa-eye-slash"></i>
                </a>
                <a href="{{ route('admin.products.active-multiple', ['active' => 1]) }}" class="btn btn-lg tooltips grey-eye mass-visible" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.activate_all_marked_elements') }}">
                    <i class="far fa-eye"></i>
                </a>

                <a href="{{ route('admin.products.delete-multiple') }}" class="btn btn-lg red btn-delete-confirm tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.common.delete_all_marked_elements') }}">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('product::admin.products.products_to_category') }}: <strong>{{ $productCategory->title }}</strong></h3>
        </div>
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <th class="width-2-percent"></th>
                    <th class="width-2-percent">{{ __('admin.number') }}</th>
                    <th>{{ __('admin.title') }}</th>
                    <th class="width-220">{{ __('admin.actions') }}</th>
                    </thead>
                    <tbody>
                    @if(!is_null($products) && $products->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($products as $product)
                            <tr class="t-row row-{{$product->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$product->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    {{ $product->title }}
                                </td>
                                <td class="pull-right">
                                    <a href="{{ route('admin.products.send-to-adboxes', ['id' => $product->id]) }}" class="btn btn-info tooltips" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="@lang('product::admin.products.make_adbox')"><i class="fas fa-ad"></i></a>
                                    <a class="btn purple-a tooltips" href="{{ route('admin.product_characteristics-by-product', ['id' => $product->id]) }}" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('product::admin.product_characteristics.product_characteristics') }}"><img src="{{ asset('admin/assets/images/product_characteristics.svg') }}" alt="Характеристики" width="16"></a>
                                    <a class="btn purple-a tooltips" href="{{ route('admin.products.combinations-by-product', ['id' => $product->id]) }}" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('product::admin.product_combinations.product_combinations') }}"><img src="{{ asset('admin/assets/images/product_combinations.svg') }}" alt="Комбинации" width="16"></a>

                                    @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(3), 'models' => $products, 'model' => $product, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$product->id}}-details hidden">
                                <td colspan="2"></td>
                                <td colspan="1">
                                    @include('admin.partials.index.table_details', ['model' => $product, 'moduleName' => 'Product'])
                                </td>
                                <td class="width-220">
                                    <img class="thumbnail img-responsive" src="{{ $product->getFileUrl() }}"/>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.products.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.products.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

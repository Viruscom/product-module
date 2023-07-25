@extends('layouts.admin.app')
@section('content')
    @include('product::admin.products.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => Request::segment(5)])

    <div class="row">
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
                    @if(!is_null($productCategory) && $productCategory->products->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($productCategory->products as $product)
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
                                    @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(5), 'models' => $productCategory->products, 'model' => $product, 'showInPublicModal' => false])
                                    <a href="{{ route('admin.products.send-to-product-adboxes', ['id' => $product->id]) }}" class="btn btn-info tooltips" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="@lang('product::admin.products.make_product_adbox')"><i class="fas fa-ad"></i></a>
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

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width: 90%; margin: 30px auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Изглед в публичната част</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
@endsection

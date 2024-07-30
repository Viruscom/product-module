@extends('layouts.admin.app')
@section('content')
    @include('product::admin.product_attributes.values.breadcrumbs')
    @include('admin.notify')

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
                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.expand_all_marked_elements') }}"></i></a>
                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="{{ __('admin.common.collapse_all_marked_elements') }}"></i></a>
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
                <a href="{{ route('admin.product-attribute.values.create',['id'=>$productAttribute->id]) }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="{{ __('admin.common.create_new') }}">
                    <i class="fas fa-plus"></i>
                </a>
                <a href="#" class="btn btn-lg tooltips red mass-delete">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <div class="hidden" id="mass-delete-url">{{ route('admin.product-attribute.values.delete-multiple',['id'=>$productAttribute->id]) }}</div>
                <a href="{{ route('admin.product-attributes.index') }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
            </div>
        </div>
    </div>

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
                    @if($productAttribute->values->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($productAttribute->values as $value)
                            <tr class="t-row row-{{$value->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="value[]" value="{{$value->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    {{ trans('product::admin.product_attribute_values.attribute') }}: {{ $productAttribute->title }}, {{ trans('product::admin.product_attribute_values.value') }}: {{ $value->title}}
                                </td>
                                <td class="pull-right">
                                    <a href="{{ route('admin.product-attribute.values.edit',['value_id'=>$value->id, 'id'=>$productAttribute->id]) }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
                                    @if($i !== 1)
                                        <a href="{{ route('admin.product-attribute.values.position-up', ['value_id'=>$value->id, 'id'=>$productAttribute->id]) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
                                    @endif
                                    @if($i != count($productAttribute->values))
                                        <a href="{{ route('admin.product-attribute.values.position-down', ['value_id'=>$value->id, 'id'=>$productAttribute->id]) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
                                    @endif
                                    <a href="{{ route('admin.product-attribute.values.delete', ['value_id'=>$value->id, 'id'=>$productAttribute->id]) }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_attribute_values.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_attribute_values.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin.app')

@section('content')
    @include('product::admin.product_attributes.breadcrumbs')
    @include('admin.notify')

    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => Request::segment(3)])

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <th class="width-2-percent"></th>
                    <th class="width-2-percent">{{ __('admin.number') }}</th>
                    <th>{{ __('admin.title') }}</th>
                    <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                    </thead>
                    <tbody>
                    @if(!is_null($productAttributes) && $productAttributes->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($productAttributes as $attribute)
                            <tr class="t-row row-{{$attribute->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$attribute->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    {{ $attribute->title }}
                                </td>
                                <td class="pull-right">
                                    <a class="btn purple-a" href="{{ route('admin.product-attribute.values.index', ['id' => $attribute->id]) }}" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="@lang('product::admin.product_attributes.attribute_values')"><i class="fa fa-bars"></i></a>

                                    @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(3), 'models' => $productAttributes, 'model' => $attribute, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$attribute->id}}-details hidden">

                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_attributes.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_attributes.no_records') }}</td>
                        </tr>
                    @endif





                    @if(!is_null($productAttributes))
                            <?php $i = 1; ?>
                        @foreach($productAttributes as $attribute)
                                <?php
                                $attributeDefaultTranslation = $attribute->defaultTranslation;
                                if (is_null($attributeDefaultTranslation)) {
                                    continue;
                                }
                                ?>
                            <tr class="t-row row-{{$attribute->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="characteristic[]" value="{{$attribute->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>{{ $attribute->title}}</td>
                                <td class="pull-right">
                                    @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(3), 'models' => $productAttributes, 'model' => $attribute, 'showInPublicModal' => false])

                                    <a class="btn purple-a" href="{{ route('products.attributes.values.index', ['attr_id'=> $attribute->id]) }}" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('product::admin.product_attributes.values_tooltip') }}"><i class="fa fa-bars"></i></a>

                                    <a href="{{ route('products.attributes.edit',['id'=>$attribute->id]) }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
                                    @if($i !== 1)
                                        <a href="{{ route('products.attributes.move-up', ['id'=>$attribute->id]) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
                                    @endif
                                    @if($i != count($productAttributes))
                                        <a href="{{ route('products.attributes.move-down', ['id'=>$attribute->id]) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
                                    @endif
                                    <a href="{{ route('products.attributes.delete', ['id'=>$attribute->id]) }}" class="btn red" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">@lang('product::admin.product_attributes.no_records')</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">@lang('product::admin.product_attributes.no_records')</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

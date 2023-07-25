@extends('layouts.admin.app')
@section('content')
    @include('product::admin.product_categories.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => Request::segment(3)])

    @if(isset($mainCategory))
        <div class="row">
            <div class="col-xs-12">
                <h3>@lang('product::admin.product_categories.sub_categories_to'): <strong>{{ $mainCategory->title }}</strong></h3>
            </div>
        </div>
    @endif
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
                    @if(!is_null($categories) && $categories->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($categories as $category)
                            <tr class="t-row row-{{$category->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$category->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    {{ $category->title }}
                                </td>
                                <td class="pull-right">
                                    <a class="btn purple-a" href="{{ route('admin.product-categories.sub-categories.index', ['id' => $category->id]) }}" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="@lang('product::admin.product_categories.show_sub_categories')"><i class="fa fa-bars"></i></a>

                                    @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(3), 'models' => $categories, 'model' => $category, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$category->id}}-details hidden">
                                <td colspan="2"></td>
                                <td colspan="1">
                                    @include('admin.partials.index.table_details', ['model' => $category, 'moduleName' => 'CategoryPage', 'hasChildrens' => true, 'childrensLabel' => trans('product::admin.products.index'), 'childrensRoute' => route('admin.products.index_by_category', ['category_id' => $category->id])])
                                </td>
                                <td class="width-220">
                                    <img class="thumbnail img-responsive" src="{{ $category->getFileUrl() }}"/>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_categories.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_categories.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

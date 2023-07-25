@extends('layouts.admin.app')
@section('content')
    @include('shop::admin.products.stocks.internal_suppliers.breadcrumbs')
    @include('admin.notify')
    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            <div class="checkbox-all pull-left p-10 p-l-0">
                <div class="pretty p-default p-square">
                    <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Маркира/Демаркира всички елементи" data-trigger="hover"/>
                    <div class="state p-primary">
                        <label></label>
                    </div>
                </div>
            </div>
            <div class="collapse-buttons pull-left p-7">
                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Разпъва всички маркирани елементи"></i></a>
                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Прибира всички маркирани елементи"></i></a>
            </div>
            <div class="search pull-left hidden-xs">
                <div class="input-group">
                    <input type="text" name="search" class="form-control input-sm search-text" placeholder="Търси">
                    <span class="input-group-btn">
					<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
				</span>
                </div>
            </div>

            <div class="action-mass-buttons pull-right">
                <a href="{{ route('admin.product-stocks.internal-suppliers.create') }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Създай нов">
                    <i class="fas fa-plus"></i>
                </a>
                {{--                <a href="{{ url('/admin/contents/active/multiple/0/') }}" class="btn btn-lg tooltips light-grey-eye mass-unvisible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като НЕ активни/видими">--}}
                {{--                    <i class="far fa-eye-slash"></i>--}}
                {{--                </a>--}}
                {{--                <a href="{{ url('/admin/contents/active/multiple/1/') }}" class="btn btn-lg tooltips grey-eye mass-visible" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Маркирай всички селектирани като активни/видими">--}}
                {{--                    <i class="far fa-eye"></i>--}}
                {{--                </a>--}}
                <a href="{{ route('admin.product-stocks.internal-suppliers.archived') }}" class="btn btn-lg tooltips btn-light-blue m-b-0" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички архивирани снабдители">
                    <i class="fas fa-trash-alt"></i>
                </a>
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
                    @if(count($suppliers))
                            <?php $i = 1; ?>
                        @foreach($suppliers as $supplier)
                            <tr class="t-row row-{{$supplier->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$supplier->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>{{ $supplier->title}}</td>
                                <td class="pull-right">
                                    <a href="{{ route('admin.product-stocks.internal-suppliers.edit', ['id' => $supplier->id]) }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
                                    {{--                                    @if(!$supplier->active)--}}
                                    {{--                                        <a href="{{ url('/admin/shop/suppliers/active/'.$supplier->id.'/1') }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>--}}
                                    {{--                                    @else--}}
                                    {{--                                        <a href="{{ url('/admin/shop/suppliers/active/'.$supplier->id.'/0') }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>--}}
                                    {{--                                    @endif--}}

                                    <a href="{{ route('admin.product-stocks.internal-suppliers.change-archive-status', ['id' => $supplier->id, 'archived' => ($supplier->archived) ? 0: 1]) }}" role="button" class="btn btn-light-blue m-b-0" data-toggle="confirmation" data-placement="auto" title="" data-original-title="Архивирай"><i class="fas fa-archive"></i></a>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="4" class="no-table-rows">{{ trans('shop::admin.internal_suppliers.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="no-table-rows">{{ trans('shop::admin.internal_suppliers.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

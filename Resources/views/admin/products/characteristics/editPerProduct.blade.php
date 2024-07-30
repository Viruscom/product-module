@extends('layouts.admin.app')
@section('content')
    @include('product::admin.products.characteristics.breadcrumbs')
    @include('admin.notify')
    <form action="{{ route('admin.product_characteristics-by-product.update', ['id'=> $mainProduct->id]) }}" method="POST">
        @csrf
        <div class="col-xs-12 p-0">
            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" href="#" class="btn btn-lg btn-light-blue m-b-0" role="button"><i class="fas fa-sync tooltips" data-toggle="tooltip" data-placement="auto" data-original-title="{{ __('product::admin.product_combinations.update_changes') }}"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <th class="width-2-percent">{{ __('admin.number') }}</th>
                        <th>{{ __('admin.title') }}</th>
                        <th>{{ __('product::admin.product_characteristics.value') }}</th>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @forelse($characteristics as $characteristic)
                                <?php
                                $characteristicTranslation = $characteristic->characteristic;
                                ?>
                            <tr class="t-row row-{{$characteristic->id}}">
                                <td class="width-2-percent text-center">{{$i}}</td>
                                <td>{{ $characteristicTranslation->title}}</td>
                                <td>
                                    <input type="text" class="form-control" name="characteristicValue[{{ $characteristic->characteristic->id }}]" value="{{ (is_null($characteristic->value)) ? '': $characteristic->value->value  }}">
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @empty
                            <tr>
                                <td colspan="4" class="no-table-rows">{{ trans('product::admin.product_characteristics.no_records_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection

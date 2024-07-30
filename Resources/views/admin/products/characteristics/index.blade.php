@extends('layouts.admin.app')

@section('content')
    @include('product::admin.products.characteristics.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.modals.delete_confirm')

    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => 'product_characteristics'])

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
                    <?php $i = 1; ?>
                    @forelse($characteristics as $characteristic)
                        <tr class="t-row row-{{$characteristic->id}}">
                            <td class="width-2-percent">
                                <div class="pretty p-default p-square">
                                    <input type="checkbox" class="checkbox-row" name="characteristic[]" value="{{$characteristic->id}}"/>
                                    <div class="state p-primary">
                                        <label></label>
                                    </div>
                                </div>
                            </td>
                            <td class="width-2-percent">{{$i}}</td>
                            <td>{{ $characteristic->title}}</td>
                            <td class="pull-right">
                                <a href="{{ route('admin.product_characteristics.edit',['id'=>$characteristic->id]) }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
                                @if(!$characteristic->active)
                                    <a href="{{ route('admin.product_characteristics.changeStatus',['id'=>$characteristic->id, 'active'=>1]) }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
                                @else
                                    <a href="{{ route('admin.product_characteristics.changeStatus',['id'=>$characteristic->id, 'active'=>0]) }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
                                @endif
                                @if($i !== 1)
                                    <a href="{{ route('admin.product_characteristics.position-up', ['id'=>$characteristic->id]) }}" role="button" class="move-up btn yellow"><i class="fas fa-angle-up"></i></a>
                                @endif
                                @if($i != count($characteristics))
                                    <a href="{{ route('admin.product_characteristics.position-down', ['id'=>$characteristic->id]) }}" role="button" class="move-down btn yellow"><i class="fas fa-angle-down"></i></a>
                                @endif
                                <a href="{{ route('admin.product_characteristics.delete', ['id'=>$characteristic->id]) }}" class="btn red btn-delete-confirm tooltips" data-toggle="confirmation"><i class="fas fa-trash-alt"></i></a>
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
@endsection

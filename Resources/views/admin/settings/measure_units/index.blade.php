@extends('layouts.admin.app')

@section('content')
    @include('product::admin.settings.measure_units.breadcrumbs')
    @include('admin.notify')

    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => Request::segment(4), 'noMultipleActive' => true])

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
                    @if(!is_null($units) && $units->isNotEmpty())
                            <?php $i = 1; ?>
                        @foreach($units as $unit)
                            <tr class="t-row row-{{$unit->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$unit->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    {{ $unit->title }}
                                </td>
                                <td class="pull-right">
                                    <a href="{{ route('admin.'.Request::segment(4).'.edit', ['id' => $unit->id]) }}" class="btn green tooltips" role="button" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.edit') }}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="{{ route('admin.'.Request::segment(4).'.delete', ['id' => $unit->id]) }}" class="btn red btn-delete-confirm tooltips" data-toggle="tooltip" data-placement="auto" title="" data-original-title="{{ __('admin.delete') }}"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$unit->id}}-details hidden">

                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="5" class="no-table-rows">{{ trans('product::admin.measure_units.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="no-table-rows">{{ trans('product::admin.measure_units.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

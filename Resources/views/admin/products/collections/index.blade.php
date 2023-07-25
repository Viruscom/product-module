@extends('layouts.admin.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/r-2.2.6/datatables.min.css"/>
    <link href="{{ asset('admin/assets/css/fixedHeader.dataTables.min.css') }}" rel="stylesheet"/>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/dataTables.fixedHeader.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable({
                collectionCellsTop: true,
                fixedHeader: true,
                language: {
                    "sProcessing": "Обработка на резултатите...",
                    "sLengthMenu": "Показване на _MENU_ резултата",
                    "sZeroRecords": "Няма намерени резултати",
                    "sInfo": "Показване на резултати от _START_ до _END_ от общо _TOTAL_",
                    "sInfoEmpty": "Показване на резултати от 0 до 0 от общо 0",
                    "sInfoFiltered": "(филтрирани от общо _MAX_ резултата)",
                    "sInfoPostFix": "",
                    "sSearch": "Търсене:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Първа",
                        "sPrevious": "Предишна",
                        "sNext": "Следваща",
                        "sLast": "Последна"
                    }
                }
            });

            // Setup - add a text input to each footer cell
            $('#example thead tr').clone(true).appendTo('#example thead');
            $('#example thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="datatable-filter-input" placeholder="филтрирай по ' + title + '" style="width:100%;" />');

                $('input', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    @include('shop::admin.products.collections.breadcrumbs')
    @include('admin.notify')
    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            <div class="search pull-left hidden-xs">
                <div class="input-group">
                    <input type="text" name="search" class="form-control input-sm search-text" placeholder="Търси" autocomplete="off">
                    <span class="input-group-btn">
					<button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
				</span>
                </div>
            </div>

            <div class="action-mass-buttons pull-right">
                <a href="{{ url('/admin/shop/collections/create') }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Създай нов">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <table id="example" class="table table-striped">
        <thead>
        <tr>
            <th>Име на колекцията</th>
            <th>Основен продукт</th>
            <th>Регистрирана на</th>
            <th class="text-right">{{ __('admin.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($collections))
            @foreach($collections as $collection)
                <tr class="t-row">
                    <td>{{ $collection->title }}</td>
                    <td>{{ $collection->mainProduct->title }}</td>
                    <td>{{ Carbon\Carbon::parse($collection->created_at)->format('d.m.Y H:m:s') }}</td>
                    <td class="text-right">
                        {{-- <a href="{{ url('/admin/shop/collections/'.$collection->id.'/show') }}" class="btn btn-primary" role="button"><i class="fas fa-binoculars"></i></a>  --}}
                        <a href="{{ route('admin.product-collections.edit', ['id' => $collection->id]) }}" class="btn green" role="button"><i class="fas fa-pencil-alt"></i></a>
                        @if(!$collection->active)
                            <a href="{{ route('admin.product-collections.changeActiveStatus', ['id' => $collection->id, 'active' => 1]) }}" role="button" class="btn light-grey-eye visibility-activate"><i class="far fa-eye-slash"></i></a>
                        @else
                            <a href="{{ route('admin.product-collections.changeActiveStatus', ['id' => $collection->id, 'active' => 0]) }}" role="button" class="btn grey-eye visibility-unactive"><i class="far fa-eye"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="no-table-rows">{{ trans('shop::admin.product_collections.no_records') }}</td>
            </tr>
        @endif
        </tbody>

    </table>
@endsection

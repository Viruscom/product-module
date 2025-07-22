@extends('layouts.admin.app')
@section('styles')
<link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/r-2.2.6/datatables.min.css" />
<link href="{{ asset('admin/assets/css/fixedHeader.dataTables.min.css') }}" rel="stylesheet" />
@endsection

@section('scripts')
<script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/dataTables.fixedHeader.min.js') }}"></script>
<script>
    $(document).ready(function () {
            function hideOffcanvas() {
                $('.offcanvas-wrapper').addClass('hidden');
            }

            $('.btn-offcanvas').on('click', function () {
                $('.offcanvas-wrapper').removeClass('hidden');
            });

            $('.offcanvas-wrapper > .caption > span').on('click', hideOffcanvas);

            $('.cancel-btn').on('click', hideOffcanvas);

            function hideOffSkuCanvas() {
                $('.offcanvas-sku-wrapper').addClass('hidden');
            }

            $('.btn-sku-offcanvas').on('click', function () {
                $('.offcanvas-sku-wrapper').removeClass('hidden');
            });

            $('.offcanvas-sku-wrapper > .caption > span').on('click', hideOffSkuCanvas);

            $('.cancel-sku-btn').on('click', hideOffSkuCanvas);

            $(".select2").select2({language: "bg"});

            $('.pcombo-mpselect').on('change', function () {
                if ($(this).val() === '') {
                    $('.attributes-wrapper').addClass('hidden');
                    $('.fields').addClass('hidden');
                    $('.actions').addClass('hidden');
                } else {
                    $.ajax({
                        url: '{{ route('admin.product-combinations.getAttributesByProductCategory') }}',
                        type: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(), product_id: $(this).val()
                        },
                        async: true,
                        success: function (response) {
                            $('.attributes-wrapper').addClass('hidden');
                            $.each(response, function (index, value) {
                                $('.pattr_' + value.pattr_id).removeClass('hidden');
                            })
                            $('.fields').removeClass('hidden');
                            $('.actions').removeClass('hidden');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                        }
                    });

                    $.ajax({
                        url: '{{ route('admin.product-combinations.getProductSkuNumber') }}',
                        type: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(), product_id: $(this).val()
                        },
                        async: true,
                        success: function (skuNumber) {
                            // if (skuNumber.product_id_code === null) {
                            //     $('#sku').val('999999999999999999');
                            // } else {
                            //     $('#sku').val(skuNumber.product_id_code);
                            // }
                        }
                    });
                }
            });

            $('[data-toggle="popover"]').popover({
                placement: 'auto',
                trigger: 'hover',
                html: true
            });

            var options = {
                withSortableRow: true,
                sortableRowFromColumn: 2,
                sortableRowToColumn: 7,
                withDynamicSort: true,
                dynamicSortFromColumn: 3,
                dynamicSortToColumn: 4,
                rowsPerPage: 50
            }
            initDatatable('example', options);

            $('.decimal').on('blur', function (e) {
                var value = $(this).val().replace(/,/g, '.');
                if (value && !value.match(/^\d*\.?\d{0,4}$/)) {
                    $(this).val('0.00');
                } else {
                    $(this).val(value);
                }
            });

            function cssInputChanged(element, initialElementValue) {
                if (element.val() !== initialElementValue) {
                    element.css({'color': '#29b6f6', 'border': '1px solid #29b6f6'});
                }
            }

            function goToNextInput(element, event, tdNumber) {
                if (event.keyCode == '13') {
                    event.preventDefault();
                    element.parent().parent().parent().next('tr').children('td:eq(' + tdNumber + ')').children('label').children('input').focus();
                }
            }

            var InputQuantity      = $('input[name="quantity"]');
            var inputQuantityValue = '';
            var InputPrice         = $('input[name="price"]');
            var inputPriceValue    = '';
            var InputSku           = $('input[name="sku"]');
            var inputSkuValue      = '';

            // InputQuantity.on('focus', function () {
            //     inputQuantityValue = $(this).val();
            // });
            //
            // InputQuantity.on('keypress', function (e) {
            //     goToNextInput($(this), e, 4);
            // });

            // InputQuantity.on('blur', function () {
            //     cssInputChanged($(this), inputQuantityValue);
            // });

            InputPrice.on('focus', function () {
                inputPriceValue = $(this).val();
            });

            InputPrice.on('keypress', function (e) {
                goToNextInput($(this), e, 4);
            });

            InputPrice.on('blur', function () {
                cssInputChanged($(this), inputPriceValue);
            });

            InputSku.on('focus', function () {
                inputSkuValue = $(this).val();
            });

            InputSku.on('keypress', function (e) {
                goToNextInput($(this), e, 5);
            });

            InputSku.on('blur', function () {
                cssInputChanged($(this), inputSkuValue);
            });

            $('.update-product-combo-mass-btn').on('click', function (e) {
                e.preventDefault();

                var combosArray = [];
                $('.checkbox-row:checked').each(function () {
                    var comboId          = this.value;
                    var element          = {};
                    element.comboId      = comboId;
                    element.quantity     = $('.quantity-' + comboId).val();
                    element.price        = $('.price-' + comboId).val();
                    element.sku          = $('.sku-' + comboId).val();
                    element.stock_status = $('.stock_status-' + comboId).val();
                    combosArray.push(element);
                });

                $.ajax({
                    url: '{{ route('admin.product-combinations.update-multiple') }}',
                    type: 'POST',
                    data: {_token: $('input[name="_token"]').val(), combos: combosArray},
                    async: false,
                    success: function (response) {
                        alert(response);
                        window.location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });
            });

            $('#selectAllCombos').on("click", function (event) {
                if (this.checked) {
                    $('.checkbox-row-combo').each(function () {
                        this.checked = true;
                    });
                } else {
                    $('.checkbox-row-combo').each(function () {
                        this.checked = false;
                    });
                }
            });
        });
</script>
@endsection
@section('content')
@include('product::admin.product_combinations.breadcrumbs')
@include('admin.notify')
<div class="col-xs-12 p-0">
    <div class="bg-grey top-search-bar">
        <div class="checkbox-all pull-left p-10 p-l-0">
            <div class="pretty p-default p-square">
                <input type="checkbox" id="selectAllCombos" class="tooltips" data-toggle="tooltip"
                    data-placement="right" data-original-title="{{ __('admin.common.mark_demark_all_elements') }}"
                    data-trigger="hover" />
                <div class="state p-primary">
                    <label></label>
                </div>
            </div>
        </div>
        <div class="collapse-buttons pull-left p-7">
            <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip"
                    data-placement="right"
                    data-original-title="{{ __('admin.common.expand_all_marked_elements') }}"></i></a>
            <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips"
                    data-toggle="tooltip" data-placement="right"
                    data-original-title="{{ __('admin.common.collapse_all_marked_elements') }}"></i></a>
        </div>
        <div class="search pull-left hidden-xs">
            <div class="input-group">
                <input type="text" name="search" class="form-control input-sm search-text"
                    placeholder="{{ __('admin.common.search') }}">
                <span class="input-group-btn">
                    <button class="btn btn-sm submit"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>

        <div class="action-mass-buttons pull-right">
            <div role="button" class="btn btn-lg tooltips green btn-offcanvas" data-toggle="tooltip"
                data-placement="auto" title="" data-original-title="{{ __('admin.common.create_new') }}">
                <i class="fas fa-plus"></i>
            </div>

            <a href="#" class="btn btn-lg btn-light-blue m-b-0 update-product-combo-mass-btn" role="button"><i
                    class="fas fa-sync tooltips" data-toggle="tooltip" data-placement="auto"
                    data-original-title="{{ __('product::admin.product_combinations.update_changes') }}"></i></a>

            <div role="button" class="btn btn-lg tooltips purple-btn btn-sku-offcanvas" data-toggle="tooltip"
                data-placement="auto" title=""
                data-original-title="{{ __('product::admin.product_combinations.generate_sku') }}">
                <img src="{{ asset('admin/assets/images/SKU_letters.svg') }}" height="20" alt="">
            </div>

            <a href="#" class="btn btn-lg tooltips red mass-delete">
                <i class="fas fa-trash-alt"></i>
            </a>
            <div class="hidden" id="mass-delete-url">{{ route('admin.product-combinations.delete-multiple') }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped" id="example">
                <thead>
                    <tr>
                        <th class="width-2-percent"></th>
                        <th class="width-2-percent">{{ __('admin.number') }}</th>
                        <th style="max-width: 250px;">{{ __('admin.title') }}</th>
                        <th>{{ __('product::admin.product_combinations.category') }}</th>
                        {{-- <th class="width-220">{{ __('product::admin.product_combinations.quantity') }}</th>--}}
                        <th class="width-220">{{ __('product::admin.product_combinations.unit_price') }}</th>
                        <th class="width-220">SKU</th>
                        <th class="width-220">{{ __('product::admin.product_combinations.status') }}</th>
                        <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($productCombinations))
                    @php
                    $i = 1;
                    @endphp
                    @foreach($productCombinations as $combination)
                    <?php
                                $combinationProduct = $combination->product;
                                $productCategory    = $combination->product->category;
                                ?>

                    <tr class="t-row row-{{$i}}">
                        <td class="width-2-percent">
                            <div class="pretty p-default p-square">
                                <input type="checkbox" class="checkbox-row checkbox-row-combo" name="ids[]"
                                    value="{{ $combination->id }}" />
                                <div class="state p-primary">
                                    <label></label>
                                </div>
                            </div>
                        </td>
                        <td class="width-2-percent">{{$i}}</td>
                        <td style="max-width: 250px;">
                            <div>
                                <div>{{ $combinationProduct->title }}
                                </div>
                                <div class="combination-details">
                                    @foreach ($combination->filter_combo as $comboProductAttributeId =>
                                    $attributeValueId)
                                    @php
                                    $attributeValue = $productAttributeValues->firstWhere('id', $attributeValueId);
                                    @endphp
                                    @if($attributeValue)
                                    <div>
                                        <span>{{ $attributeValue->parent->title }}:</span>
                                        <span>{{ $attributeValue->title }}</span>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>{{ $productCategory->title }}</td>
                        <form id="form-{{$combination->id}}"
                            action="{{ route('admin.product-combinations.update', ['id'=> $combination->id]) }}"
                            method="POST">
                            @csrf
                            {{-- <td class="text-center">--}}
                                {{-- <p class="m-b-0">Количество: {{ $combination->quantity }}</p>--}}
                                {{-- <label>--}}
                                    {{-- <input type="number" name="quantity"
                                        class="decimal text-center quantity-{{$combination->id}}"
                                        value="{{ old('quantity') ?? $combination->quantity }}">--}}
                                    {{-- </label>--}}
                                {{-- </td>--}}
                            <td class="text-right">
                                <p class="m-b-0">{{ __('product::admin.product_combinations.unit_price') }}: {{
                                    $combination->price }}</p>
                                <label>
                                    <input type="number" name="price" step="0.01"
                                        class="decimal text-right price-{{$combination->id}}"
                                        value="{{ old('price') ?? ($combination->price == '' ? '0.00': $combination->price) }}">
                                </label>
                            </td>
                            <td class="text-right">
                                <p class="m-b-0">SKU: {{ $combination->sku }}</p>
                                <label>
                                    <input type="text" name="sku" class="text-right sku-{{$combination->id}}"
                                        value="{{ old('sku') ?? $combination->sku }}">
                                </label>
                            </td>
                            <td class="text-right">
                                <p class="m-b-0">{{ __('product::admin.product_combinations.status') }}:
                                    @if(!is_null($combination->stock_status))
                                    @lang('front.stock_status_'.$combination->stock_status)
                                    @endif
                                </p>
                                <select id="stock_status" class="form-control stock_status-{{$combination->id}}"
                                    name="stock_status" style="max-width: 200px;">
                                    <option value="1" {{(old('stock_status')) || $combination->stock_status == 1 ?
                                        'selected': ''}}>@lang('front.stock_status_1')</option>
                                    <option value="2" {{(old('stock_status')) || $combination->stock_status == 2 ?
                                        'selected': ''}}>@lang('front.stock_status_2')</option>
                                    <option value="3" {{(old('stock_status')) || $combination->stock_status == 3 ?
                                        'selected': ''}}>@lang('front.stock_status_3')</option>
                                    <option value="4" {{(old('stock_status')) || $combination->stock_status == 4 ?
                                        'selected': ''}}>@lang('front.stock_status_4')</option>
                                    <option value="5" {{(old('stock_status')) || $combination->stock_status == 5 ?
                                        'selected': ''}}>@lang('front.stock_status_5')</option>
                                </select>
                            </td>
                            <td class="pull-right">
                                <p></p>
                                <button type="submit"
                                    href="{{ route('admin.product-combinations.update', ['id'=> $combination->id]) }}"
                                    class="btn btn-light-blue m-b-0 update-product-combo-btn" role="button"><i
                                        class="fas fa-sync tooltips" data-toggle="tooltip" data-placement="auto"
                                        data-original-title="{{ __('product::admin.product_combinations.update_changes') }}"></i></button>
                                <a href="{{ route('admin.product-combinations.delete', ['id'=> $combination->id]) }}"
                                    class="btn red delete-product-combo-btn" data-toggle="confirmation"><i
                                        class="fas fa-trash-alt tooltips" data-toggle="tooltip" data-placement="auto"
                                        data-original-title="{{ __('product::admin.product_combinations.delete') }}"></i></a>
                            </td>
                        </form>
                    </tr>

                    <?php $i++; ?>
                    @endforeach

                    @else
                    <tr>
                        <td colspan="8" class="no-table-rows">{{ trans('product::admin.product_combinations.no_records')
                            }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="offcanvas-wrapper hidden">
    <div class="caption">{{ __('product::admin.product_combinations.add') }}
        <span>X</span>
    </div>
    <form action="{{ route('admin.product-combinations.generate') }}" method="POST">
        @csrf
        <div class="mb-1">
            <label for="main_product_id" class="form-label w-100" style="display: flex">1.
                @lang('product::admin.product_combinations.step_1')</label>
            <select name="main_product_id" class="select2 form-control m-b-20 pcombo-mpselect"
                style="display: flex;width: 100%" id="main_product_id">
                <option value="">{{ __('admin.common.please_select') }}</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->title }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-top: 20px;margin-bottom: 5px;">
            <b>2. @lang('product::admin.product_combinations.step_2')</b>
        </div>
        @if($productAttributes)
        @foreach($productAttributes as $productAttribute)
        <div class="attributes-wrapper pattr_{{$productAttribute->id}} hidden">
            <div class="caption">{{ $productAttribute->title }}</div>
            <div class="attributes">
                @foreach($productAttribute->values as $productAttributeValue)
                <div class="pretty p-default p-square"
                    style="{{ $productAttribute->type == 3 ? 'display:flex;justify-content:space-between;' :'' }} margin-right:0;"
                    @if($productAttribute->type == 3 && $productAttributeValue->filename != '') data-toggle="popover"
                    data-content='<img class="img-responsive" src="{{ $productAttributeValue->getFileUrl() }}" />'
                    @endif>
                    <input type="checkbox" class="checkbox-row" name="attribute[{{$productAttribute->id}}][]"
                        value="{{ $productAttributeValue->id }}" />
                    <div class="state p-primary">
                        <label>
                            {{ $productAttributeValue->title }}
                        </label>
                    </div>
                    <div>
                        @if($productAttribute->type == 3)
                        <div class="attribute-value-color-img-wrapper">
                            @if($productAttributeValue->filename == '')
                            <div style="background-color: {{ $productAttributeValue->color_picker_color }}"></div>
                            @else
                            <img src="{{ $productAttributeValue->getFileUrl() }}" width="16">
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        @endif

        <div style="margin-top: 20px;margin-bottom: 5px;">
            <b>3. Добавете стойности</b>
        </div>
        <div class="fields hidden">
            <div class="mb-1 m-t-20">
                <label for="quantity" class="form-label w-100">{{ __('product::admin.product_combinations.quantity')
                    }}</label>
                <input type="number" name="quantity" class="decimal form-control m-b-20" id="quantity" min="1" value="1"
                    autocomplete="off">
            </div>
            <div class="mb-1">
                <label for="unit_price" class="form-label w-100">{{ __('product::admin.product_combinations.unit_price')
                    }}</label>
                <input type="text" name="price" class="decimal form-control m-b-20" id="unit_price" value="0.00"
                    autocomplete="off">
            </div>
            <div class="mb-1">
                <label for="sku" class="form-label w-100">SKU</label>
                <input type="text" name="sku" class="form-control m-b-20" id="sku" value="" autocomplete="off">
            </div>
            <div class="mb-1">
                <label for="sku" class="form-label w-100">{{ __('product::admin.product_combinations.status') }}</label>
                <select id="stock_status" class="form-control m-b-20 w-100" name="stock_status" autocomplete="off">
                    <option value="1" {{(old('stock_status')) ? 'selected' : '' }}>@lang('front.stock_status_1')
                    </option>
                    <option value="2" {{(old('stock_status')) ? 'selected' : '' }}>@lang('front.stock_status_2')
                    </option>
                    <option value="3" {{(old('stock_status')) ? 'selected' : '' }}>@lang('front.stock_status_3')
                    </option>
                    <option value="4" {{(old('stock_status')) ? 'selected' : '' }}>@lang('front.stock_status_4')
                    </option>
                    <option value="5" {{(old('stock_status')) ? 'selected' : '' }}>@lang('front.stock_status_5')
                    </option>
                </select>
            </div>
        </div>

        <div style="margin-top: 20px;margin-bottom: 5px;">
            <b>4. {{ __('product::admin.product_combinations.generate_combinations') }}</b>
        </div>
        <div class="actions hidden">
            <button type="submit" class="btn btn-success generate-btn">{{
                __('product::admin.product_combinations.generate') }}</button>
            <div class="btn btn-default cancel-btn">{{ __('product::admin.product_combinations.cancel') }}</div>
        </div>
    </form>
</div>

<div class="offcanvas-sku-wrapper hidden">
    <div class="caption">{{ __('product::admin.product_combinations.sku_generator') }}
        <span>X</span>
    </div>
    <form action="{{ route('admin.product-combinations.generate-sku-numbers-by-product') }}" method="POST">
        @csrf
        <div class="m-b-10">
            <label for="sku_product_id" class="form-label w-100" style="display: flex">1. {{
                __('product::admin.product_combinations.choose_product') }}</label>
            <select name="sku_product_id" class="select2 form-control m-b-20" style="display: flex;width: 100%"
                id="sku_product_id" required>
                <option value="">{{ __('admin.common.please_select') }}</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="m-b-10">
            <label for="prefix" class="form-label w-100">2. {{ __('product::admin.product_combinations.add_prefix')
                }}</label>
            <input type="text" name="prefix" class="form-control m-b-20" id="prefix" value="" autocomplete="off"
                required>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-success">{{ __('product::admin.product_combinations.generate')
                }}</button>
            <div class="btn btn-default cancel-sku-btn">{{ __('product::admin.product_combinations.cancel') }}</div>
        </div>
    </form>
</div>
@endsection
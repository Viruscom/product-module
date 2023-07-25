@extends('layouts.admin.app')

@section('content')
    @include('shop::admin.products.stocks.breadcrumbs')
    @include('admin.notify')

    <div class="col-xs-12 p-0">
        <div class="bg-grey top-search-bar">
            {{--            <div class="checkbox-all pull-left p-10 p-l-0">--}}
            {{--                <div class="pretty p-default p-square">--}}
            {{--                    <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Маркира/Демаркира всички елементи" data-trigger="hover"/>--}}
            {{--                    <div class="state p-primary">--}}
            {{--                        <label></label>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <div class="collapse-buttons pull-left p-7">--}}
            {{--                <a class="btn btn-xs expand-btn"><i class="fas fa-angle-down fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Разпъва всички маркирани елементи"></i></a>--}}
            {{--                <a class="btn btn-xs collapse-btn hidden"><i class="fas fa-angle-up fa-2x" class="tooltips" data-toggle="tooltip" data-placement="right" data-original-title="Прибира всички маркирани елементи"></i></a>--}}
            {{--            </div>--}}

            <div class="action-mass-buttons pull-right">
                <a href="{{ route('admin.product-stocks.internal-suppliers.create') }}" role="button" class="btn btn-lg tooltips green" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Създай нов снабдител">
                    <i class="fas fa-plus"></i>
                </a>
                <a href="{{ route('admin.product-stocks.internal-suppliers.index') }}" class="btn btn-lg tooltips btn-light-blue m-b-0" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички снабдители">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <a href="{{ route('admin.product-stocks.internal-suppliers.archived') }}" class="btn btn-lg tooltips btn-light-blue m-b-0" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички архивирани снабдители">
                    <i class="fas fa-trash-alt"></i>
                </a>
                {{--                <a href="{{ route('products.stocks.movements.index') }}" class="btn btn-lg tooltips btn-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички движения"><i class="fas fa-history"></i></a>--}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 mt-3 m-b-40">
            <h4>Филтър</h4>
            <div class="bg-grey p-2" style="display: flex;justify-content: space-between;height: auto;flex-direction: column;padding: 10px;">
                <div style="display: flex;">
                    <input type="text" name="name" class="form-control" placeholder="Въведете име на продукт" autocomplete="off">
                    <input type="text" name="sku" class="form-control" placeholder="Въведете SKU номер" autocomplete="off">
                    <select type="text" name="category_id" class="form-control">
                        <option value="">---Моля, изберете категория---</option>
                        @foreach($productCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary submit m-l-5"><i class="fa fa-search"></i></button>
                </div>
                <div class="m-t-10" style="display: flex;">
                    @foreach($productAttributes as $attribute)
                        <div>
                            <label for="">{{ $attribute->title }}</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                                @foreach($attribute->values as $value)
                                    <option value="{{ $value->id }}">{{ $value->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <th class="width-2-percent"></th>
                    <th class="width-2-percent">Ред</th>
                    <th>Заглавие</th>
                    <th class="width-130 text-right">SKU номер</th>
                    <th class="width-130 text-right">Наличност</th>
                    <th class="width-130 text-right">Действия</th>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($products as $product)
                            <?php
                            $combosCount = $product->combinations->isNotEmpty();
                            ?>
                        <tr class="t-row row-{{$product->id}}">
                            <td class="width-2-percent"></td>
                            <td class="width-2-percent">{{$i}}</td>
                            <td>
                                <img src="{{ $product->getFileUrl() }}" height="30" class="m-r-5">
                                {{ $product->title}}
                                @if(!is_null($product->combinations) && $product->combinations->isNotEmpty())
                                    Брой комбинации: {{$product->combinations->count()}}
                                @endif
                            </td>
                            <td class="width-130 text-right">{{ $product->sku }}</td>
                            <td class="width-130 text-right">
                                <input type="number" value="{{ $product->units_in_stock }}" name="new_quantity" style="width: 90px">
                            </td>
                            <td class="pull-right">
                                <a href="#" class="btn tooltips btn-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички движения за продукта"><i class="fas fa-history"></i></a>
                            </td>
                        </tr>
                        @if(!is_null($product->combinations) && $product->combinations->isNotEmpty())
                            <tr class="t-row-details row-{{$product->id}}-details hidden">
                                <td colspan="6" class="p-0">
                                    <div style="padding: 0px 2%;border-top: 1px solid #c8286440;">
                                        <h4>Продуктови комбинации</h4>
                                    </div>
                                    <table style="min-width: 100%; max-width: 100%;">
                                        <tbody>
                                        @foreach($product->combinations as $combo)
                                                <?php
                                                $combinationProduct = $combo->product;
                                                $productCategory    = $combo->product->category;
                                                ?>
                                            <tr class="product-combos-tr">
                                                <td class="width-2-percent"></td>
                                                <td class="width-2-percent"></td>
                                                <td>
                                                    <div>{{ $product->title }}</div>
                                                    <div class="combination-details">
                                                        @foreach ($combo->combination as $comboProductAttributeId => $attributeValueId)
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
                                                </td>
                                                <td class="width-130 text-right">{{ $combo->sku }}</td>
                                                <td class="width-130 text-right">
                                                    <input type="number" value="{{ $combo->quantity }}" name="new_quantity" style="width: 90px">
                                                </td>
                                                <td class="width-130 text-right">
                                                    <a href="{{ $combo->id }}" class="btn tooltips btn-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Виж всички движения за продукта"><i class="fas fa-history"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </td>

                            </tr>
                        @endif
                            <?php $i++; ?>
                    @endforeach
                    <tr style="display: none;">
                        <td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

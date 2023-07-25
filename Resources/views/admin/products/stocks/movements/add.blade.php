@extends('layouts.app')
@section('styles')
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});

        $(document).ready(function () {

        });
    </script>
@endsection
@section('content')
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

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submitaddnew" value="submitaddnew" class="btn btn-lg green saveplusicon margin-bottom-10"></button>
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url('/admin/navigations') }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <form action="#">
            <div class="col-md-4">
                <div class="form-group @if($errors->has('video_link')) has-error @endif">
                    <label class="control-label">Дата на заприхождаване:</label>
                    <input class="form-control" type="text" name="video_link" value="{{ old('video_link') }}">
                    @if($errors->has('video_link'))
                        <span class="help-block">{{ trans($errors->first('video_link')) }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group @if($errors->has('note')) has-error @endif">
                    <label class="control-label">Забележка:</label>
                    <textarea class="form-control" type="text" name="note">{{ old('note') }}</textarea>
                    @if($errors->has('note'))
                        <span class="help-block">{{ trans($errors->first('note')) }}</span>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 mt-3 m-b-40">
                <h4>Филтър</h4>
                <div class="bg-grey p-2" style="display: flex;justify-content: space-between;height: auto;flex-direction: column;padding: 10px;">
                    <div style="display: flex;">
                        <input type="text" name="name" class="form-control" placeholder="Въведете име на продукт" autocomplete="off">
                        <input type="text" name="sku" class="form-control" placeholder="Въведете SKU номер" autocomplete="off">
                        <select type="text" name="category_id" class="form-control">
                            <option value="">---Моля, изберете---</option>
                        </select>
                        <button class="btn btn-sm btn-primary submit m-l-5"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="m-t-10" style="display: flex;">
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Атрибут 1</label>
                            <select name="" id="" class="form-control">
                                <option value="">---Моля, изберете---</option>
                            </select>
                        </div>
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
                        <th>Снабдител</th>
                        <th class="width-130 text-right">SKU номер</th>
                        <th class="width-130 text-right">Текуща наличност</th>
                        <th class="width-130 text-right">Заприхождаване</th>
                        </thead>
                        <tbody>
                        <?php $i = 1;?>
                        @foreach($products as $product)
                            <?php
                            $productDefaultTranslation = $product->defaultTranslation;
                            if (is_null($productDefaultTranslation)) {
                                continue;
                            }
                            $combosCount = count($product->combos);
                            ?>
                            <tr class="t-row row-{{$product->id}}">
                                <td class="width-2-percent"></td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>
                                    <img src="{{ $product->fullImageFilePathUrl() }}" height="30" class="m-r-5">
                                    {{ $productDefaultTranslation->title}}
                                    @if($combosCount > 0)
                                    @endif
                                </td>
                                <td>
                                    <select name="supplier" class="select2 width-220" id="">
                                        @foreach($internalSuppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->defaultTranslation->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="width-130 text-right">{{ $product->sku }}</td>
                                <td class="width-130 text-right">{{ $product->units_in_stock }}</td>
                                <td class="width-130 text-right">
                                    <input type="number" value="" name="new_quantity" style="width: 90px">
                                </td>
                            </tr>
                            @if($combosCount > 0)
                                <tr class="t-row-details row-{{$product->id}}-details hidden">
                                    <td colspan="7" class="p-0">
                                        <div style="padding: 0px 2%;border-top: 1px solid #c8286440;">
                                            <h4>Продуктови комбинации</h4>
                                        </div>
                                        <table style="min-width: 100%; max-width: 100%;">
                                            <tbody>
                                            @foreach($product->combos as $combo)
                                                <tr class="product-combos-tr">
                                                    <td class="width-2-percent"></td>
                                                    <td class="width-2-percent"></td>
                                                    <td>
                                                        <div>{{ $productDefaultTranslation->title }}</div>
                                                        <div class="combination-details">
                                                            @foreach($combo->combination as $key=>$combinationValue)
                                                                @if($combinationValue != $product->id)
                                                                    @foreach($productAttributeValues as $productAttributeId=>$attributeValueArray)
                                                                        @foreach($attributeValueArray as $attributeValue)
                                                                            @if($attributeValue->id == $combinationValue)
                                                                                <div>
                                                                                    <span>{{ $attributeValue->productAttribute->defaultTranslation->title }}:</span>
                                                                                    <span>{{ $attributeValue->defaultTranslation->title }}</span>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select name="supplier" class="select2 width-220" id="">
                                                            @foreach($internalSuppliers as $supplier)
                                                                <option value="{{ $supplier->id }}">{{ $supplier->defaultTranslation->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="width-130 text-right">{{ $combo->sku }}</td>
                                                    <td class="width-130 text-right">{{ $combo->quantity }}</td>
                                                    <td class="width-130 text-right">
                                                        <input type="number" value="" name="new_quantity" style="width: 90px">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                            @endif
                            <?php $i++;?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="5" class="no-table-rows">{{ trans('administration_messages.no_recourds_found') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

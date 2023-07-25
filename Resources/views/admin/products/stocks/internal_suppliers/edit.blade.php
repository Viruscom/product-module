@extends('layouts.admin.app')

@section('styles')
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/shop/js/supplier.js') }}"></script>
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});
    </script>
@endsection

@section('content')
    @include('shop::admin.products.stocks.internal_suppliers.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.product-stocks.internal-suppliers.update', ['id' => $supplier->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{ old('position') }}">

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ route('admin.product-stocks.internal-suppliers.index') }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <ul class="nav nav-tabs">
                    @foreach($languages as $language)
                        <li @if($language->code === config('default.app.language.code')) class="active" @endif><a data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span class="err-span-{{$language->code}} hidden text-purple"><i class="fas fa-exclamation"></i></span></a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($languages as $language)
                        @php
                            $langTitle = 'title_'.$language->code;
                            $langRegAddress = 'registration_address_'.$language->code;
                            $internalSupplierTranslation = $supplier->translations->where('locale', $language->code)->first();
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            <div class="form-group @if($errors->has($langTitle)) has-error @endif">
                                <label class="control-label p-b-10">Заглавие (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                <input class="form-control" type="text" name="{{$langTitle}}" value="{{ old($langTitle) ?? (!is_null($internalSupplierTranslation) ? $internalSupplierTranslation->title : '') }}" required>
                                @if($errors->has($langTitle))
                                    <span class="help-block">{{ trans($errors->first($langTitle)) }}</span>
                                @endif
                            </div>

                            <div class="form-group @if($errors->has($langRegAddress)) has-error @endif">
                                <label class="control-label p-b-10">Адрес по регистрация (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                <input class="form-control" type="text" name="{{$langRegAddress}}" value="{{ old($langRegAddress) ?? (!is_null($internalSupplierTranslation) ? $internalSupplierTranslation->registration_address : '') }}" required>
                                @if($errors->has($langRegAddress))
                                    <span class="help-block">{{ trans($errors->first($langRegAddress)) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form form-horizontal">
                    <div class="form-body">
                        <div class="form-group @if($errors->has('eik')) has-error @endif">
                            <label class="control-label col-md-3">ЕИК:</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" name="eik" value="{{ old('eik') ?? $supplier->eik }}" required>
                            </div>
                            @if($errors->has('eik'))
                                <span class="help-block">{{ trans($errors->first('eik')) }}</span>
                            @endif
                        </div>

                        <div class="form-group @if($errors->has('vat_number')) has-error @endif">
                            <label class="control-label col-md-3">ДДС номер:</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" name="vat_number" value="{{ old('vat_number') ?? $supplier->vat_number }}" required>
                            </div>
                            @if($errors->has('vat_number'))
                                <span class="help-block">{{ trans($errors->first('vat_number')) }}</span>
                            @endif
                        </div>

                        <div class="form-group @if($errors->has('phone')) has-error @endif">
                            <label class="control-label col-md-3">Телефон:</label>
                            <div class="col-md-3">
                                <input class="form-control" type="tel" name="phone" value="{{ old('phone') ?? $supplier->phone }}" required>
                            </div>
                            @if($errors->has('phone'))
                                <span class="help-block">{{ trans($errors->first('phone')) }}</span>
                            @endif
                        </div>

                        <div class="form-group @if($errors->has('email')) has-error @endif">
                            <label class="control-label col-md-3">e-mail:</label>
                            <div class="col-md-3">
                                <input class="form-control" type="email" name="email" value="{{ old('email') ?? $supplier->email }}" required>
                            </div>
                            @if($errors->has('email'))
                                <span class="help-block">{{ trans($errors->first('email')) }}</span>
                            @endif
                        </div>
                        {{--                        <hr>--}}
                        {{--                        <div class="form-group">--}}
                        {{--                            <label class="control-label col-md-3">Активен (видим) в сайта:</label>--}}
                        {{--                            <div class="col-md-6">--}}
                        {{--                                <label class="switch pull-left">--}}
                        {{--                                    <input type="checkbox" name="active" class="success" data-size="small" {{ old('active') ? 'checked' : ($supplier->active ? 'checked': '') }}>--}}
                        {{--                                    <span class="slider"></span>--}}
                        {{--                                </label>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <hr>--}}
                        {{--                        <div class="form-group">--}}
                        {{--                            <label class="control-label col-md-3">Позиция в сайта:</label>--}}
                        {{--                            <div class="col-md-6">--}}
                        {{--                                <p class="position-label"></p>--}}
                        {{--                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal">Моля, изберете позиция</a>--}}
                        {{--                                <p class="help-block">(ако не изберете позиция, записът се добавя като последен)</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" name="submit" value="submit" class="btn save-btn margin-bottom-10"><i class="fas fa-save"></i> запиши</button>
                                <a href="{{ route('admin.product-stocks.internal-suppliers.store') }}" role="button" class="btn back-btn margin-bottom-10"><i class="fa fa-reply"></i> назад</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            {{--            <div id="myModal" class="modal fade" role="dialog">--}}
            {{--                <div class="modal-dialog">--}}

            {{--                    <!-- Modal content-->--}}
            {{--                    <div class="modal-content">--}}
            {{--                        <div class="modal-header">--}}
            {{--                            <button type="button" class="close text-purple" data-dismiss="modal">&times;</button>--}}
            {{--                            <h4 class="modal-title">Изберете позиция</h4>--}}
            {{--                        </div>--}}
            {{--                        <div class="modal-body">--}}
            {{--                            <table class="table table-striped table-hover table-positions">--}}
            {{--                                <tbody>--}}
            {{--                                @if(count($suppliers))--}}
            {{--                                    @foreach($suppliers as $supplier)--}}
            {{--                                        <tr class="pickPositionTr" data-position="{{$supplier->position}}">--}}
            {{--                                            <td>{{$supplier->position}}</td>--}}
            {{--                                            <td>{{$supplier->translations->firstWhere('language_id',$defaultLanguage->id)->title}}</td>--}}
            {{--                                        </tr>--}}
            {{--                                    @endforeach--}}
            {{--                                    <tr class="pickPositionTr" data-position="{{$suppliers->last()->position+1}}">--}}
            {{--                                        <td>{{$suppliers->last()->position+1}}</td>--}}
            {{--                                        <td>--{{trans('administration_messages.last_position')}}--</td>--}}
            {{--                                    </tr>--}}
            {{--                                @else--}}
            {{--                                    <tr class="pickPositionTr" data-position="1">--}}
            {{--                                        <td>1</td>--}}
            {{--                                        <td>--{{trans('administration_messages.last_position')}}--</td>--}}
            {{--                                    </tr>--}}
            {{--                                @endif--}}
            {{--                                </tbody>--}}
            {{--                            </table>--}}
            {{--                            <div class="form-actions">--}}
            {{--                                <div class="row">--}}
            {{--                                    <div class="col-md-offset-3 col-md-9">--}}
            {{--                                        <a href="#" class="btn save-btn margin-bottom-10 accept-position-change" data-dismiss="modal"><i class="fas fa-save"></i> потвърди</a>--}}
            {{--                                        <a role="button" class="btn back-btn margin-bottom-10 cancel-position-change" current-position="{{ old('position') }}" data-dismiss="modal"><i class="fa fa-reply"></i> назад</a>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </div>
    </form>
@endsection

@extends('layouts.admin.app')

@section('styles')
    <link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
    <script>
        $(".select2").select2({language: "bg"});
    </script>
@endsection

@section('content')
    @include('shop::admin.products.collections.breadcrumbs')
    @include('admin.notify')
    <div class="col-xs-12 p-0">
        <form class="my-form" action="{{ route('admin.product-collections.store') }}" method="POST" data-form-type="store" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{old('position')}}">

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submitaddnew" value="submitaddnew" class="btn btn-lg green saveplusicon margin-bottom-10"></button>
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="col-md-12 col-xs-12 alert alert-warning"><strong>Внимание! </strong>Видими са само продуктите, които са активни(видими в сайта), имат въведена цена и са в наличност</div>
            <div class="form form-horizontal">
                <div class="form-group @if($errors->has('title')) has-error @endif">
                    <label class="control-label col-md-3 col-xs-6"><span class="text-purple">* </span>Име на колекцията</label>
                    <div class="col-md-4 col-xs-6">
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Въведете име. Името е видимо само в административната част">
                        @if($errors->has('title'))
                            <span class="help-block">{{ trans($errors->first('title')) }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group @if($errors->has('main_product_id')) has-error @endif">
                    <label class="control-label col-md-3 col-xs-6"><span class="text-purple">* </span>Основен продукт</label>
                    <div class="col-md-4 col-xs-6">
                        <select name="main_product_id" id="" class="form-control select2">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('main_product_id'))
                            <span class="help-block">{{ trans($errors->first('main_product_id')) }}</span>
                        @endif
                    </div>
                </div>

                @for($i=0; $i<5; $i++)
                    <div class="form-group @if($errors->has('main_product_id')) has-error @endif">
                        <label class="control-label col-md-3 col-xs-6">Допълнителен продукт</label>
                        <div class="col-md-4 col-xs-4">
                            <select name="additionalProducts[{{$i}}][product_id]" id="" class="form-control select2">
                                @if($i)
                                    <option value="">--- Моля, изберете ---</option>
                                @endif
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('main_product_id'))
                                <span class="help-block">{{ trans($errors->first('main_product_id')) }}</span>
                            @endif
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <input type="number" step="0.01" name="additionalProducts[{{$i}}][discount]" class="form-control" placeholder="Въведете отстъпка (10.20)">
                        </div>
                    </div>
                @endfor

                <div class="form-body">
                    <hr>
                    <div class="form-group">
                        <label class="control-label col-md-3">Активен (видим) в сайта:</label>
                        <div class="col-md-6">
                            <label class="switch pull-left">
                                <input type="checkbox" name="active" class="success" data-size="small" checked checked {{(old('active') ? 'checked' : 'active')}}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" name="submitaddnew" value="submitaddnew" class="btn green saveplusbtn margin-bottom-10"> запиши и добави нов</button>
                            <button type="submit" name="submit" value="submit" class="btn save-btn margin-bottom-10"><i class="fas fa-save"></i> запиши</button>
                            <a href="{{ url()->previous() }}" role="button" class="btn back-btn margin-bottom-10"><i class="fa fa-reply"></i> назад</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection

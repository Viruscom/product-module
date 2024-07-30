@extends('layouts.admin.app')

@section('styles')
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        try {
            CKEDITOR.timestamp = new Date();
            CKEDITOR.replace('editor');
        } catch {
        }
        $(".select2").select2({language: "bg"});
    </script>
@endsection

@section('content')
    @include('product::admin.product_attributes.breadcrumbs')
    @include('admin.notify')

    <form class="my-form" action="{{ route('admin.product-attributes.store') }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{old('position')}}">

            @include('admin.partials.on_create.form_actions_top')
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
                            $langTitle = 'title_'.$language->code
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            <div class="form-group @if($errors->has($langTitle)) has-error @endif">
                                <label class="control-label p-b-10">@lang('admin.title') (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                <input class="form-control" type="text" name="{{$langTitle}}" value="{{ old($langTitle) }}">
                                @if($errors->has($langTitle))
                                    <span class="help-block">{{ trans($errors->first($langTitle)) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form form-horizontal">
                    <div class="form-body">
                        <hr>
                        <div style="display: flex; justify-content: space-between;border-bottom: 2px solid #cecece;">
                            <h4>{{ __('product::admin.product_categories.associate') }}</h4>
                            <div style="display: flex;">
                                <div class="checkbox-all pull-left p-10 p-l-0">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="auto" data-original-title="Маркира/Демаркира всички катеегории" data-trigger="hover"/>
                                        <div class="state p-primary">
                                            <label>{{ __('product::admin.product_categories.mark_unmark') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex;flex-wrap: wrap;row-gap: 10px;" class="m-t-20">
                            @forelse($productCategories as $category)
                                <div class="pretty p-default p-square">
                                    <input type="checkbox" class="checkbox-row" name="productCategories[]" value="{{$category->id}}"{{ old('productCategories') ? (in_array($category->id, old('productCategories')) ? 'checked':''):'' }}/>
                                    <div class="state p-primary">
                                        <label>{{ $category->title }}</label>
                                    </div>
                                </div>
                                @if($category->subCategories->isNotEmpty())
                                    @foreach($category->subCategories as $category)
                                        <div class="pretty p-default p-square">
                                            <input type="checkbox" class="checkbox-row" name="productCategories[]" value="{{$category->id}}"{{ old('productCategories') ? (in_array($category->id, old('productCategories')) ? 'checked':''):'' }}/>
                                            <div class="state p-primary">
                                                <label>{{ $category->title }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @empty
                                <div class="alert alert-warning">{{ __('product::admin.product_categories.no_active_categories') }}</div>
                            @endforelse
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="select-id" class="control-label col-md-3">@lang('product::admin.product_attributes.type'):</label>
                            <div class="col-md-4">
                                <select name="type" id="select-id" class="form-control">
                                    <option value="1">@lang('product::admin.product_attributes.type_1')</option>
                                    <option value="2">@lang('product::admin.product_attributes.type_2')</option>
                                    <option value="3">@lang('product::admin.product_attributes.type_3')</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        @include('admin.partials.on_create.active_checkbox')
                        <hr>
                        @include('admin.partials.on_create.position_in_site_button')
                    </div>
                    @include('admin.partials.on_create.form_actions_bottom')
                </div>
            </div>

            @include('admin.partials.modals.positions_on_create', ['parent' => $characteristics])

        </div>
    </form>
@endsection

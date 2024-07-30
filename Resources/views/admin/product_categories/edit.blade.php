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
    @include('product::admin.product_categories.breadcrumbs')
    @include('admin.notify')

    <form class="my-form" action="{{ route('admin.product-categories.update', ['id' => $category->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{(old('position')) ?: $category->position}}">

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>
        @if(!is_null($category->main_category))
            <div class="row">
                <div class="col-xs-12">
                    <h3>@lang('product::admin.product_categories.edit_sub_category_to'): <strong>{{ $category->mainCategory->title }}</strong></h3>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">{{ __('admin.common.warning_title_change') }}</div>
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
                            $categoryTranslate = is_null($category->translate($language->code)) ? $category : $category->translate($language->code);
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">

                            @include('admin.partials.on_edit.form_fields.input_text', ['model'=> $categoryTranslate, 'fieldName' => 'title_' . $language->code, 'label' => trans('admin.title'), 'required' => true])
                            @include('admin.partials.on_edit.form_fields.textarea_without_ckeditor', ['model'=> $categoryTranslate, 'fieldName' => 'announce_' . $language->code, 'rows' => 4, 'label' => trans('admin.announce'), 'required' => false])
                            @include('admin.partials.on_edit.form_fields.textarea', ['model'=> $categoryTranslate, 'fieldName' => 'description_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false])
                            @include('admin.partials.on_edit.show_in_language_visibility_checkbox', ['model'=> $category, 'fieldName' => 'visible_' . $language->code])

                        </div>
                    @endforeach
                </div>
                @include('admin.partials.on_edit.seo', ['model' => $category->seoFields])
                <div class="form form-horizontal">
                    <div class="form-body">
                        @include('admin.partials.on_edit.form_fields.upload_file', ['model' => $category, 'deleteRoute' => route('admin.product-categories.delete-image', ['id'=>$category->id])])
                        <hr>
                        @include('admin.partials.on_edit.active_checkbox', ['model' => $category])
                        <hr>
                        @include('admin.partials.on_edit.position_in_site_button', ['model' => $category, 'models' => $categories])

                    </div>
                    @include('admin.partials.on_edit.form_actions_bottom')
                </div>
            </div>
        </div>
    </form>
@endsection

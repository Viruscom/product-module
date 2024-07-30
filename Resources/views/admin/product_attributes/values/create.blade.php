@extends('layouts.admin.app')

@section('styles')
    <link href="{{ asset('admin/plugins/colorpicker/jquery.minicolors.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/colorpicker/jquery.minicolors.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('.demo').each(function () {
                //
                // Dear reader, it's actually very easy to initialize MiniColors. For example:
                //
                //  $(selector).minicolors();
                //
                // The way I've done it below is just for the demo, so don't get confused
                // by it. Also, data- attributes aren't supported at this time...they're
                // only used for this demo.
                //
                $(this).minicolors({
                    control: $(this).attr('data-control') || 'hue',
                    defaultValue: $(this).attr('data-defaultValue') || '',
                    format: $(this).attr('data-format') || 'hex',
                    keywords: $(this).attr('data-keywords') || '',
                    inline: $(this).attr('data-inline') === 'true',
                    letterCase: $(this).attr('data-letterCase') || 'lowercase',
                    opacity: $(this).attr('data-opacity'),
                    position: $(this).attr('data-position') || 'bottom',
                    swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                    change: function (value, opacity) {
                        if (!value) return;
                        if (opacity) value += ', ' + opacity;
                        if (typeof console === 'object') {
                        }
                    },
                    theme: 'bootstrap'
                });

            });

        });
    </script>
@endsection

@section('content')
    @include('product::admin.product_attributes.values.breadcrumbs')
    @include('admin.notify')

    <form class="my-form" action="{{ route('admin.product-attribute.values.store', ['id' => $productAttribute->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
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
                <h4>{{ __('product::admin.product_attribute_values.create') }}: {{ $productAttribute->title }}</h4>
            </div>
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
                                <label class="control-label p-b-10">{{ __('admin.title') }} (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                <input class="form-control" type="text" name="{{$langTitle}}" value="{{ old($langTitle) }}">
                                @if($errors->has($langTitle))
                                    <span class="help-block">{{ trans($errors->first($langTitle)) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($productAttribute->type == 3)
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group @if($errors->has('color_picker_color')) has-error @endif">
                            <label for="text-field" class="control-label p-b-10">{{ __('product::admin.product_attribute_values.color') }}:</label>
                            <input type="text" id="text-field" class="form-control demo" value="#70c24a" name="color_picker_color" required>
                            @if($errors->has('color_picker_color'))
                                <span class="help-block">{{ trans($errors->first('color_picker_color')) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="form-group @if($errors->has('image')) has-error @endif">
                            <label class="control-label p-b-10">{{ __('product::admin.product_attribute_values.file') }}:</label>
                            <input type="file" name="image" class="filestyle" data-buttonText="{{trans('admin.browse_file')}}" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                            @if($errors->has('image'))
                                <span class="help-block">{{ trans($errors->first('image')) }}</span>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="form form-horizontal">
                    <div class="form-body">
                        <hr>
                        @include('admin.partials.on_create.position_in_site_button')
                    </div>
                    @include('admin.partials.on_create.form_actions_bottom')
                </div>
            </div>
            @include('admin.partials.modals.positions_on_create', ['parent' => $productAttribute->values])
        </div>
    </form>
@endsection

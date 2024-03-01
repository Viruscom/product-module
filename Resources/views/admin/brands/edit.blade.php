@extends('layouts.admin.app')

@section('scripts')
    <script src="{{ asset('admin/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        try {
            CKEDITOR.timestamp = new Date();
            CKEDITOR.replace('editor');
        } catch {
        }
    </script>
@endsection

@section('content')
    @include('product::admin.brands.breadcrumbs')
    @include('admin.notify')

    <form class="my-form" action="{{ route('admin.brands.update', ['id' => $brand->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{(old('position')) ?: $brand->position}}">

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">{!! __('admin.common.warning_title_change') !!}</div>
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
                            $brandTranslate = is_null($brand->translate($language->code)) ? $brand : $brand->translate($language->code);
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            @include('admin.partials.on_edit.form_fields.input_text', ['model'=> $brandTranslate, 'fieldName' => 'title_' . $language->code, 'label' => trans('admin.title'), 'required' => true])
                            @include('admin.partials.on_edit.form_fields.textarea_without_ckeditor', ['model'=> $brandTranslate, 'fieldName' => 'announce_' . $language->code, 'rows' => 4, 'label' => trans('admin.announce'), 'required' => false])
                            @include('admin.partials.on_edit.form_fields.textarea', ['model'=> $brandTranslate, 'fieldName' => 'description_' . $language->code, 'rows' => 9, 'label' => trans('admin.description'), 'required' => false])
                            @include('admin.partials.on_edit.show_in_language_visibility_checkbox', ['model'=> $brand, 'fieldName' => 'visible_' . $language->code])
                        </div>
                    @endforeach
                </div>
                @include('admin.partials.on_edit.seo', ['model' => $brand->seoFields])
                <div class="form form-horizontal">
                    <div class="form-body">
                        @include('admin.partials.on_edit.form_fields.upload_file', ['model' => $brand, 'deleteRoute' => route('admin.brands.delete-image', ['id'=>$brand->id])])
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-3">{{ __('admin.common.logo') }}:</label>
                            <div class="col-md-9">
                                <input type="file" name="logo_image" class="filestyle" data-buttonText="{{ trans('admin.browse_file') }}" data-iconName="fas fa-upload" data-buttonName="btn green" data-badge="true">
                                <p class="help-block">{!! $fileRulesInfo !!}</p>
                                <div>
                                    <img class="thumbnail img-responsive" src="{{ $brand->getLogoUrl() }}" width="300" alt="{{ __('admin.image') }}"/>
                                    @if ($brand->existsFile($brand->logo_filename))
                                        <a href="{{ route('admin.brands.delete-logo', ['id' => $brand->id])  }}" class="btn btn-danger"><i class="fas fa-times"></i> {{ __('admin.delete_image') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">{{ __('admin.common.show_logo_in_site') }}:</label>
                            <div class="col-md-6">
                                <label class="switch pull-left">
                                    <input type="checkbox" name="logo_active" class="success" data-size="small" {{(old('logo_active') || $brand->logo_active ? 'checked' : '')}}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <hr>
                        @include('admin.partials.on_edit.active_checkbox', ['model' => $brand])
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-md-3">{{ __('admin.position_in_site') }}:</label>
                            <div class="col-md-6">
                                <p class="position-label">№ {{ $brand->position }}</p>
                                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal">{{ __('admin.please_choose_position') }}</a>
                                <p class="help-block">{{ __('admin.position_description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" name="submit" value="submit" class="btn save-btn margin-bottom-10"><i class="fas fa-save"></i> {{ __('admin.common.save') }}</button>
                                <a href="{{ url()->previous() }}" role="button" class="btn back-btn margin-bottom-10"><i class="fa fa-reply"></i> {{ __('admin.common.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close text-purple" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">{{ __('admin.choose_position') }}</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-hover table-positions">
                                <tbody>
                                @if(count($brands))
                                    @foreach($brands as $brand)
                                        <tr class="pickPositionTr" data-position="{{$brand->position}}">
                                            <td>{{$brand->position}}</td>
                                            <td>{{$brand->title}}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="pickPositionTr" data-position="{{$brands->last()->position+1}}">
                                        <td>{{$brands->last()->position+1}}</td>
                                        <td>--{{trans('admin.common.last_position')}}--</td>
                                    </tr>
                                @else
                                    <tr class="pickPositionTr" data-position="1">
                                        <td>1</td>
                                        <td>--{{trans('admin.common.last_position')}}--</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <a href="#" class="btn save-btn margin-bottom-10 accept-position-change" data-dismiss="modal"><i class="fas fa-save"></i> {{ __('admin.common.apply') }}</a>
                                        <a role="button" class="btn back-btn margin-bottom-10 cancel-position-change" current-position="{{ $brand->position }}" data-dismiss="modal"><i class="fa fa-reply"></i> {{ __('admin.common.back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    </div>
@endsection

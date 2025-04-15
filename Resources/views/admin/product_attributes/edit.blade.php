@extends('layouts.admin.app')

@section('content')
@include('product::admin.product_attributes.breadcrumbs')
@include('admin.notify')
<form class="my-form" action="{{ route('admin.product-attributes.update', ['id'=>$productAttribute->id]) }}"
    method="POST" data-form-type="store" enctype="multipart/form-data">
    <div class="col-xs-12 p-0">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="position" value="{{(old('position')) ?: $productAttribute->position}}">

        <div class="bg-grey top-search-bar">
            <div class="action-mass-buttons pull-right">
                <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i
                        class="fas fa-save"></i></button>
                <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i
                        class="fa fa-reply"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <ul class="nav nav-tabs">
                @foreach($languages as $language)
                <li @if($language->code === config('default.app.language.code')) class="active" @endif><a
                        data-toggle="tab" href="#{{$language->code}}">{{$language->code}} <span
                            class="err-span-{{$language->code}} hidden text-purple"><i
                                class="fas fa-exclamation"></i></span></a></li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($languages as $language)
                @php
                $langTitle = 'title_'.$language->code;
                $productAttributeTranslation = is_null($productAttribute->translate($language->code)) ?
                $productAttribute : $productAttribute->translate($language->code);
                @endphp
                <div id="{{$language->code}}"
                    class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                    <div class="form-group @if($errors->has($langTitle)) has-error @endif">
                        <label class="control-label p-b-10">{{ __('shop::admin.common.title') }} (<span
                                class="text-uppercase">{{$language->code}}</span>):</label>
                        <input class="form-control" type="text" name="{{$langTitle}}"
                            value="{{ old($langTitle) ?: $productAttributeTranslation->title }}">
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
                    @php
                    function renderCategories($categories, $selectedProductCategories) {
                    foreach ($categories as $category) {
                    echo '<div class="pretty p-default p-square">';
                        echo '<input type="checkbox" class="checkbox-row" name="productCategories[]"
                            value="' . $category->id . '"' . (in_array($category->id, $selectedProductCategories) ? '
                            checked' : '' ) . '/>' ; echo '<div class="state p-primary"><label>' . $category->title .
                        '</label></div>';
                    echo '
                </div>';
                if ($category->subCategories->isNotEmpty()) {
                echo '<div class="sub-categories-wrapper">';
                    renderCategories($category->subCategories, $selectedProductCategories);
                    echo '</div>';
                }
                }
                }
                @endphp

                <div style="display: flex; justify-content: space-between;border-bottom: 2px solid #cecece;">
                    <h4>Асоциирай към продуктова категория</h4>
                    <div style="display: flex;">
                        <div class="checkbox-all pull-left p-10 p-l-0">
                            <div class="pretty p-default p-square">
                                <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip"
                                    data-placement="auto" data-original-title="Маркира/Демаркира всички категории"
                                    data-trigger="hover" />
                                <div class="state p-primary">
                                    <label>Маркирай/Демаркирай всички категории</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display: flex;flex-wrap: wrap;row-gap: 10px;" class="m-t-20">
                    @forelse($productCategories as $category)
                    @if(is_null($category->main_category))
                    <div class="category-group-wrapper">
                        @php
                        renderCategories([$category], $selectedProductCategories);
                        @endphp
                    </div>
                    @endif
                    @empty
                    <div class="alert alert-warning">Няма добавени или активни категории</div>
                    @endforelse
                </div>

                <hr>
                <div class="form-group">
                    <label for="select-id" class="control-label col-md-3">Тип:</label>
                    <div class="col-md-4">
                        <select name="type" id="select-id" class="form-control">
                            <option value="1" {{ old('type') ?? $productAttribute->type == 1 ? 'selected':''
                                }}>@lang('product::admin.product_attributes.type_1')</option>
                            <option value="2" {{ old('type') ?? $productAttribute->type == 2 ? 'selected':''
                                }}>@lang('product::admin.product_attributes.type_2')</option>
                            <option value="3" {{ old('type') ?? $productAttribute->type == 3 ? 'selected':''
                                }}>@lang('product::admin.product_attributes.type_3')</option>
                        </select>
                    </div>
                </div>
                <hr>
                @include('admin.partials.on_edit.active_checkbox', ['model' => $productAttribute])
                <hr>
                @include('admin.partials.on_edit.position_in_site_button', ['model' => $productAttribute, 'models' =>
                $characteristics])
            </div>

            @include('admin.partials.on_edit.form_actions_bottom')
        </div>
    </div>

    @include('admin.partials.modals.positions_on_edit', ['parent' => $characteristics, 'model' => $productAttribute])
    </div>
</form>
@endsection
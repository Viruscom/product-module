@extends('layouts.admin.app')

@section('content')
    @include('product::admin.products.characteristics.breadcrumbs')
    @include('admin.notify')
    <form class="my-form" action="{{ route('admin.product_characteristics.update', ['id'=>$productCharacteristic->id]) }}" method="POST" data-form-type="store" enctype="multipart/form-data">
        <div class="col-xs-12 p-0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="position" value="{{(old('position')) ?: $productCharacteristic->position}}">

            <div class="bg-grey top-search-bar">
                <div class="action-mass-buttons pull-right">
                    <button type="submit" name="submit" value="submit" class="btn btn-lg save-btn margin-bottom-10"><i class="fas fa-save"></i></button>
                    <a href="{{ url()->previous() }}" role="button" class="btn btn-lg back-btn margin-bottom-10"><i class="fa fa-reply"></i></a>
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
                            $productCharacteristicTranslation = is_null($productCharacteristic->translate($language->code)) ? $productCharacteristic : $productCharacteristic->translate($language->code);
                        @endphp
                        <div id="{{$language->code}}" class="tab-pane fade in @if($language->code === config('default.app.language.code')) active @endif">
                            <div class="form-group @if($errors->has($langTitle)) has-error @endif">
                                <label class="control-label p-b-10">{{ __('shop::admin.common.title') }} (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                <input class="form-control" type="text" name="{{$langTitle}}" value="{{ old($langTitle) ?: $productCharacteristicTranslation->title }}">
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
                                        <input type="checkbox" id="selectAll" class="tooltips" data-toggle="tooltip" data-placement="auto" data-original-title="{{ __('product::admin.product_categories.mark_unmark') }}" data-trigger="hover"/>
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
                                    <input type="checkbox" class="checkbox-row" name="productCategories[]" value="{{$category->id}}" {{ in_array($category->id, $selectedProductCategories) ? 'checked':'' }}/>
                                    <div class="state p-primary">
                                        <label>{{ $category->title }}</label>
                                    </div>
                                </div>
                                @if($category->subCategories->isNotEmpty())
                                    @foreach($category->subCategories as $category)
                                        <div class="pretty p-default p-square">
                                            <input type="checkbox" class="checkbox-row" name="productCategories[]" value="{{$category->id}}" {{ in_array($category->id, $selectedProductCategories) ? 'checked':'' }}/>
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
                        @include('admin.partials.on_edit.active_checkbox', ['model' => $productCharacteristic])
                        <hr>
                        @include('admin.partials.on_edit.position_in_site_button', ['model' => $productCharacteristic, 'models' => $characteristics])
                    </div>

                    @include('admin.partials.on_edit.form_actions_bottom')
                </div>
            </div>

            @include('admin.partials.modals.positions_on_edit', ['parent' => $characteristics, 'model' => $productCharacteristic])
        </div>
    </form>
@endsection

<div class="panel-group" id="additional_fields">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 data-toggle="collapse" data-parent="#additional_fields" href="#additional_fields_collapse-{{$language->id}}-1" class="panel-title expand">
                <a href="#">{{ __('product::admin.products.additional_fields') }}</a>
            </h4>
        </div>
        <div id="additional_fields_collapse-{{$language->id}}-1" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row">
                    @for($f=1; $f<=$maxFields; $f++)
                        @php
                            $langAdditionalFieldTitle = 'additional_field_title_' . $f .'_'.$language->code;
                            $langAdditionalFieldAmount = 'additional_field_amount_' . $f .'_'.$language->code;
                            $additionalField = isset($isCreate) ? null :$product->additionalFields()->where('field_id', $f)->where('locale', $language->code)->first()
                        @endphp
                        <div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has($langAdditionalFieldTitle)) has-error @endif">
                                    <label class="control-label p-b-10">{{ __('product::admin.products.additional_field_title') }} {{$f}} (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                    <input class="form-control" type="text" name="{{$langAdditionalFieldTitle}}" value="{{ old($langAdditionalFieldTitle) ? old($langAdditionalFieldTitle) : (!is_null($additionalField) ? $additionalField->name : '') }}">
                                    @if($errors->has($langAdditionalFieldTitle))
                                        <span class="help-block">{{ trans($errors->first($langAdditionalFieldTitle)) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has($langAdditionalFieldAmount)) has-error @endif">
                                    <label class="control-label p-b-10">{{ __('product::admin.products.additional_field_value') }} {{$f}} (<span class="text-uppercase">{{$language->code}}</span>):</label>
                                    <input class="form-control" type="text" name="{{$langAdditionalFieldAmount}}" value="{{ old($langAdditionalFieldTitle) ? old($langAdditionalFieldTitle) : (!is_null($additionalField) ? $additionalField->text : '') }}">
                                    @if($errors->has($langAdditionalFieldAmount))
                                        <span class="help-block">{{ trans($errors->first($langAdditionalFieldAmount)) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

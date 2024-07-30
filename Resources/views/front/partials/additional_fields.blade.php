@if($product->additionalFields->count())
    <ul class="list-info">
        @php $c=1; @endphp
        @for($f=1; $f<=10; $f++)
            @php
                $field = $product->additionalFields()->where('locale', $languageSlug)->where('field_id', $f)->first();
                if(is_null($field)){
                    continue;
                }
            @endphp

            @if($field->name != "" && $field->text != "")
                @if($c == 1)
                    <li>
                        @endif
                        <strong>{!! $field->name !!}</strong>
                        <span>{!! $field->text !!}</span>
                        @php
                            $c++;
                            if($c==2){$c=1;}
                        @endphp
                        @if($c == 1)
                    </li>
                @endif
            @endif

        @endfor
    </ul>
@endif

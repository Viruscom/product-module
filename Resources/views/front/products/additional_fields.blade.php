<div class="prod-cols" data-aos="fade-up" data-aos-delay="50">
    @php
        $additionalFields = $product->getAdditionalFields($languageSlug);
    @endphp
    @foreach($additionalFields as $field)
        <div class="prod-col">
            <p>{{ $field->name }}:</p>
            <span>{{ $field->text }}</span>
        </div>
    @endforeach
</div>

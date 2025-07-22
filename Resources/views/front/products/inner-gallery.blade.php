<div class="product-image">
    <ul class="main-image slider-for">
        @if($mainGallery->isNotEmpty())
        @foreach($mainGallery as $index => $image)
        <li class="slide prod-gallery-item">
            <a href="{{ $image->getFileUrl() }}" gallery-id="1" img-id="{{ $index }}">
                <img src="{{ $image->getFileUrl() }}" alt="{{ $image->title }}">
            </a>
        </li>
        @endforeach
        @else
        <li class="slide prod-gallery-item">
            <img src="{{ $currentModel->parent->getSystemImage() }}" alt="">
        </li>
        @endif
    </ul>

    <ul class="slider-nav">
        @if($mainGallery->isNotEmpty())
        @foreach($mainGallery as $image)
        <li class="slide">
            <img class="img-responsive" src="{{ $image->getFileUrl() }}" alt="{{ $image->title }}">
        </li>
        @endforeach
        @else
        <li class="slide">
            <img class="img-responsive" src="{{ $currentModel->parent->getSystemImage() }}" alt="">
        </li>
        @endif
    </ul>

    <div class="hidden-elements lightGallery hidden gallery-1">
        @if($mainGallery->isNotEmpty())
        @foreach($mainGallery as $index => $image)
        <li data-responsive="{{ $image->getFileUrl() }}" data-src="{{ $image->getFileUrl() }}"
            data-sub-html="{{ $image->title }}">
            <a href="{{ $image->getFileUrl() }}" class="img-id-{{ $index }}">
                <img class="img-responsive" src="{{ $image->getFileUrl() }}" alt="{{ $image->title }}">
            </a>
        </li>
        @endforeach
        @else
        <li data-responsive="{{ $currentModel->parent->getSystemImage() }}"
            data-src="{{ $currentModel->parent->getSystemImage() }}">
            <img class="img-responsive" src="{{ $currentModel->parent->getSystemImage() }}" alt="">
        </li>
        @endif
    </div>
</div>
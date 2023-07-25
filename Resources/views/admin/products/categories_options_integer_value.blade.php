<option value="{{ $category->id }}" {{ (old('category_id') === $category->id || isset($productCategoryId) && $productCategoryId === $category->id) ? 'selected':'' }}>
    @php
        $prefix = implode('.', $depth);
    @endphp
    {{ $prefix }} {{ $category->title }}
</option>
@if($category->subCategories->isNotEmpty())
    <optgroup label="{{ $category->title }} - Подкатегории">
        @foreach($category->subCategories as $index => $subCategory)
            @include('shop::admin.products.categories_options_integer_value', ['category' => $subCategory, 'depth' => [...$depth, $index + 1]])
        @endforeach
    </optgroup>
@endif

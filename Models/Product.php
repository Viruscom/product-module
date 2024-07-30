<?php

    namespace Modules\Product\Models;

    use App\Helpers\AdminHelper;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\FileDimensionHelper;
    use App\Helpers\SeoHelper;
    use App\Models\Seo;
    use App\Traits\CommonActions;
    use App\Traits\HasModelRatios;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Support\Str;
    use Modules\Product\Entities\Settings\MeasureUnit;
    use Modules\Product\Models\Admin\Brands\Brand;
    use Modules\Product\Models\Admin\ProductCategory\Category;
    use Modules\Product\Models\Admin\ProductCombination\ProductCombination;
    use Modules\Product\Models\Admin\Products\ProductAdditionalField;
    use Modules\Product\Models\Admin\Products\ProductTranslation;

    class Product extends Model
    {
        use HasModelRatios;

        public const FILES_PATH = "images/products/products";

        const        ALLOW_CATALOGS = true;
        const        ALLOW_ICONS    = true;
        const        ALLOW_LOGOS    = true;

        public static string $PRODUCT_SYSTEM_IMAGE = 'product_3_image.png';

        public array $translatedAttributes = ['title', 'announce', 'description', 'visible', 'url', 'title_additional_1', 'title_additional_2', 'title_additional_3',
                                              'title_additional_4', 'title_additional_5', 'title_additional_6', 'text_additional_1', 'text_additional_2',
                                              'text_additional_3', 'text_additional_4', 'text_additional_5', 'text_additional_6'];
        protected    $fillable             = ['active', 'position', 'filename', 'creator_user_id', 'logo_filename', 'logo_active', 'category_id', 'brand_id',
                                              'supplier_delivery_price', 'price', 'barcode', 'ean_code', 'measure_unit_id', 'is_new', 'is_promo', 'width', 'height', 'length', 'weight', 'sku', 'units_in_stock', 'measure_unit_value', 'catalog_from_price', 'catalog_discounted_price', 'catalog_from_discounted_price'];
        protected    $table                = 'products';

        public static function getFileRules(): string
        {
            return FileDimensionHelper::getRules('Product', 3);
        }

        public static function getUserInfoMessage(): string
        {
            return FileDimensionHelper::getUserInfoMessage('Product', 3);
        }

        public static function cacheUpdate(): void
        {
            cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_ADMIN);
            cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_FRONT);
            cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_ADMIN, function () {
                return self::with('category')->with('brand', 'measureUnit')->withTranslation()->with('translations')->orderBy('position')->get();
            });

            cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_FRONT, function () {
                return self::with('category', 'category.translations')->with('brand', 'measureUnit')->active(true)->orderBy('position')->with('translations')->get();
            });
        }

        public static function getRequestData($request): array
        {
            $data = [
                'category_id'     => $request->category_id,
                'measure_unit_id' => $request->measure_unit_id,
                'brand_id'        => $request->brand_id,
                'position'        => $request->position,
                'creator_user_id' => Auth::user()->id
            ];

            $data['active'] = false;
            if ($request->has('active')) {
                $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('filename')) {
                $data['filename'] = $request->filename;
            }

            if ($request->has('supplier_delivery_price')) {
                $data['supplier_delivery_price'] = $request->supplier_delivery_price;
            }

            if ($request->has('price')) {
                $data['price'] = $request->price;
            }

            if ($request->has('barcode')) {
                $data['barcode'] = $request->barcode;
            }

            if ($request->has('ean_code')) {
                $data['ean_code'] = $request->ean_code;
            }

            if ($request->has('sku')) {
                $data['sku'] = $request->sku;
            }

            if ($request->has('measure_unit')) {
                $data['measure_unit'] = $request->measure_unit;
            }

            if ($request->has('measure_unit_value')) {
                $data['measure_unit_value'] = $request->measure_unit_value;
            }

            $data['is_new'] = false;
            if ($request->has('is_new')) {
                $data['is_new'] = filter_var($request->is_new, FILTER_VALIDATE_BOOLEAN);
            }

            $data['is_promo'] = false;
            if ($request->has('is_promo')) {
                $data['is_promo'] = filter_var($request->is_promo, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('width')) {
                $data['width'] = $request->width;
            }

            if ($request->has('height')) {
                $data['height'] = $request->height;
            }

            if ($request->has('length')) {
                $data['length'] = $request->length;
            }

            if ($request->has('weight')) {
                $data['weight'] = $request->weight;
            }

            if ($request->has('units_in_stock')) {
                $data['units_in_stock'] = $request->units_in_stock;
            }

            $data['catalog_from_price'] = false;
            if ($request->has('catalog_from_price')) {
                $data['catalog_from_price'] = filter_var($request->catalog_from_price, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('catalog_discounted_price')) {
                $data['catalog_discounted_price'] = $request->catalog_discounted_price;
            }

            $data['catalog_from_discounted_price'] = false;
            if ($request->has('catalog_from_discounted_price')) {
                $data['catalog_from_discounted_price'] = filter_var($request->catalog_from_discounted_price, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->hasFile('image')) {
                $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
            }

            return $data;
        }

        public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
        {
            foreach ($languages as $language) {
                $data[$language->code] = ProductTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
            }

            return $data;
        }

        public static function generatePosition($request)
        {
            $models = self::where('category_id', $request->category_id)->orderBy('position', 'desc')->get();
            if (count($models) < 1) {
                return 1;
            }
            if (!$request->has('position') || is_null($request['position'])) {
                return $models->first()->position + 1;
            }

            if ($request['position'] > $models->first()->position) {
                return $models->first()->position + 1;
            }
            $modelsToUpdate = self::where('category_id', $request->category_id)->where('position', '>=', $request['position'])->get();
            foreach ($modelsToUpdate as $modelToUpdate) {
                $modelToUpdate->update(['position' => $modelToUpdate->position + 1]);
            }

            return $request['position'];
        }

        public static function allocateModule($viewArray)
        {
            switch (class_basename($viewArray['currentModel']->parent)) {
                case 'Product':
                    return view('product::front.products.show', ['viewArray' => $viewArray]);
                case 'Brand':
                    return view('product::front.brands.show', ['viewArray' => $viewArray]);
                case 'Category':
                    $categories = Admin\ProductCategory\Category::where('active', true)->whereNull('main_category')->with('translations')->with(['subCategories' => function ($q) {
                        return $q->orderBy('position');
                    }])->orderBy('position')->get();
                    $brands     = Brand::where('active', true)->with('translations')->orderBy('position')->get();

                    return view('product::front.categories.show', ['viewArray' => $viewArray, 'categories' => $categories, 'brands' => $brands]);
                default:
                    abort(404);
            }
        }

        public static function getProductBrandsSpecialPage($viewArray)
        {
            return view('product::front.product_special_page', [
                'viewArray' => $viewArray,
                'brands'    => Brand::where('active', true)->orderBy('position', 'asc')->get()
            ]);
        }

        public function updatedPosition($request)
        {
            if (!$request->has('position') || is_null($request->position) || $request->position == $this->position) {
                return $this->position;
            }

            $models = self::where('category_id', $this->category_id)->orderBy('position', 'desc')->get();
            if (count($models) == 1) {
                $request['position'] = 1;

                return $request['position'];
            }

            if ($request['position'] > $models->first()->position) {
                $request['position'] = $models->first()->position;
            } elseif ($request['position'] < $models->last()->position) {
                $request['position'] = $models->last()->position;
            }

            if ($request['position'] >= $this->position) {
                $modelsToUpdate = self::where('category_id', $this->category_id)->where('id', '<>', $this->id)->where('position', '>', $this->position)->where('position', '<=', $request['position'])->get();
                foreach ($modelsToUpdate as $modelToUpdate) {
                    $modelToUpdate->update(['position' => $modelToUpdate->position - 1]);
                }

                return $request['position'];
            }

            $modelsToUpdate = self::where('category_id', $this->category_id)->where('id', '<>', $this->id)->where('position', '<', $this->position)->where('position', '>=', $request['position'])->get();
            foreach ($modelsToUpdate as $modelToUpdate) {
                $modelToUpdate->update(['position' => $modelToUpdate->position + 1]);
            }

            return $request['position'];
        }

        /**
         * @return BelongsTo
         */
        public function category(): BelongsTo
        {
            return $this->belongsTo(Category::class)->with('translations');
        }

        /**
         * @return BelongsTo
         */
        public function brand(): BelongsTo
        {
            return $this->belongsTo(Brand::class);
        }

        public function getFilepath($filename): string
        {
            return $this->getFilesPath() . $filename;
        }

        public function getFilesPath(): string
        {
            return self::FILES_PATH . '/' . $this->id . '/';
        }

        public function getAnnounce(): string
        {
            return Str::limit($this->announce, 255, ' ...');
        }

        public function getPrice()
        {
            return number_format($this->price, 2, '.', '');
        }

        public function getEncryptedPath($moduleName): string
        {
            return encrypt($moduleName . '-' . get_class($this) . '-' . $this->id);
        }

        public function headerGallery()
        {
            return $this->getHeaderGalleryRelation(get_class($this));
        }

        public function mainGallery()
        {
            return $this->getMainGalleryRelation(get_class($this));
        }

        public function additionalGalleryOne()
        {
            return $this->getAdditionalGalleryOneRelation(get_class($this));
        }

        public function additionalGalleryTwo()
        {
            return $this->getAdditionalGalleryTwoRelation(get_class($this));
        }

        public function additionalGalleryThree()
        {
            return $this->getAdditionalGalleryThreeRelation(get_class($this));
        }

        public function additionalGalleryFour()
        {
            return $this->getAdditionalGalleryFourRelation(get_class($this));
        }

        public function additionalGalleryFive()
        {
            return $this->getAdditionalGalleryFiveRelation(get_class($this));
        }

        public function additionalGallerySix()
        {
            return $this->getAdditionalGallerySixRelation(get_class($this));
        }

        public function seoFields()
        {
            return $this->hasOne(Seo::class, 'model_id')->where('model', get_class($this));
        }

        public function seo($languageSlug)
        {
            $seo = $this->seoFields;
            if (is_null($seo)) {
                return null;
            }
            SeoHelper::setSeoFields($this, $seo->translate($languageSlug));
        }

        public function isNewProduct(): bool
        {
            return (boolean)$this->is_new;
        }

        public function isPromoProduct(): bool
        {
            return (boolean)$this->is_promo;
        }

        public function isInCollection(): bool
        {
            //TODO: Make collection check
            return false;
        }

        public function scopeIsInStock($query)
        {
            return $query->where('units_in_stock', '>', 0);
        }

        public function updateUnitsInStock($newQuantity): void
        {
            $this->update(['units_in_stock' => $newQuantity]);
        }

        public function additionalFields(): HasMany
        {
            return $this->hasMany(ProductAdditionalField::class, 'product_id', 'id');
        }

        public function getAdditionalFields($languageSlug)
        {
            return $this->hasMany(ProductAdditionalField::class, 'product_id', 'id')->where('locale', $languageSlug)->whereNotNull(['name', 'text'])->get();
        }

        public function getPreviousProductUrl($languageSlug)
        {
            if ($this->position == 1) {
                return null;
            }
            $previousProduct = $this->category->products()->where('position', $this->position - 1)->first();
            if (is_null($previousProduct)) {
                return null;
            }

            return $previousProduct->getUrl($languageSlug);
        }

        public function getUrl($languageSlug)
        {
            return url($languageSlug . '/' . $this->url);
        }

        public function getNextProductUrl($languageSlug)
        {
            $query       = $this->category->products();
            $lastProduct = $query->latest()->first();
            if (is_null($lastProduct) || $this->position == $lastProduct->position) {
                return null;
            }

            $nextProduct = $query->where('position', $this->position + 1)->first();
            if (is_null($nextProduct)) {
                return null;
            }

            return $nextProduct->getUrl($languageSlug);
        }

        public function measureUnit(): HasOne
        {
            return $this->hasOne(MeasureUnit::class, 'id', 'measure_unit_id')->with('translations');
        }

        public function combinations(): HasMany
        {
            return $this->hasMany(ProductCombination::class, 'product_id', 'id');
        }

        public function setKeys($array): array
        {
            $array[1]['sys_image_name'] = trans('product::admin.product_brands.index');
            $array[1]['sys_image']      = Brand::$BRAND_SYSTEM_IMAGE;
            $array[1]['sys_image_path'] = AdminHelper::getSystemImage(Brand::$BRAND_SYSTEM_IMAGE);
            $array[1]['field_name']     = 'brand';
            $array[1]['ratio']          = self::getModelRatio('brand');
            $array[1]['mimes']          = self::getModelMime('brand');
            $array[1]['max_file_size']  = self::getModelMaxFileSize('brand');
            $array[1]['file_rules']     = 'mimes:' . self::getModelMime('brand') . '|size:' . self::getModelMaxFileSize('brand') . '|dimensions:ratio=' . self::getModelRatio('brand');

            $array[2]['sys_image_name'] = trans('product::admin.product_categories.index');
            $array[2]['sys_image']      = Category::$PRODUCT_CATEGORY_SYSTEM_IMAGE;
            $array[2]['sys_image_path'] = AdminHelper::getSystemImage(Category::$PRODUCT_CATEGORY_SYSTEM_IMAGE);
            $array[2]['field_name']     = 'product_category';
            $array[2]['ratio']          = self::getModelRatio('product_category');
            $array[2]['mimes']          = self::getModelMime('product_category');
            $array[2]['max_file_size']  = self::getModelMaxFileSize('product_category');
            $array[2]['file_rules']     = 'mimes:' . self::getModelMime('product_category') . '|size:' . self::getModelMaxFileSize('product_category') . '|dimensions:ratio=' . self::getModelRatio('product_category');

            $array[3]['sys_image_name'] = trans('product::admin.products.index');
            $array[3]['sys_image']      = Product::$PRODUCT_SYSTEM_IMAGE;
            $array[3]['sys_image_path'] = AdminHelper::getSystemImage(Product::$PRODUCT_SYSTEM_IMAGE);
            $array[3]['field_name']     = 'product';
            $array[3]['ratio']          = self::getModelRatio('product');
            $array[3]['mimes']          = self::getModelMime('product');
            $array[3]['max_file_size']  = self::getModelMaxFileSize('product');
            $array[3]['file_rules']     = 'mimes:' . self::getModelMime('product') . '|size:' . self::getModelMaxFileSize('product') . '|dimensions:ratio=' . self::getModelRatio('product');

            return $array;
        }

        public function getSystemImage(): string
        {
            return AdminHelper::getSystemImage(self::$PRODUCT_SYSTEM_IMAGE);
        }
    }

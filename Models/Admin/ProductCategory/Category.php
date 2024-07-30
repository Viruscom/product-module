<?php

    namespace Modules\Product\Models\Admin\ProductCategory;

    use App\Helpers\AdminHelper;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\FileDimensionHelper;
    use App\Helpers\SeoHelper;
    use App\Interfaces\Models\ImageModelInterface;
    use App\Models\Seo;
    use App\Traits\CommonActions;
    use App\Traits\HasGallery;
    use App\Traits\Scopes;
    use App\Traits\StorageActions;
    use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
    use Astrotomic\Translatable\Translatable;
    use Auth;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasManyThrough;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttribute;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttributePivot;
    use Modules\Product\Models\Admin\Products\Product;

    class Category extends Model implements TranslatableContract, ImageModelInterface
    {
        use Translatable, Scopes, StorageActions, CommonActions, HasGallery;

        public const FILES_PATH     = "images/products/product_categories";
        const        ALLOW_CATALOGS = true;
        const        ALLOW_ICONS    = true;
        const        ALLOW_LOGOS    = true;

        public static string $PRODUCT_CATEGORY_SYSTEM_IMAGE  = 'product_2_image.png';
        public static string $PRODUCT_CATEGORY_RATIO         = '1/1';
        public static string $PRODUCT_CATEGORY_MIMES         = 'jpg,jpeg,png,gif,webp,svg';
        public static string $PRODUCT_CATEGORY_MAX_FILE_SIZE = '3000';

        public array $translatedAttributes = ['title', 'announce', 'description'];
        protected    $fillable             = ['main_category', 'active', 'position', 'filename', 'creator_user_id'];
        protected    $table                = 'product_categories';

        public static function getFileRules(): string
        {
            return FileDimensionHelper::getRules('Product', 2);
        }

        public static function getUserInfoMessage(): string
        {
            return FileDimensionHelper::getUserInfoMessage('Product', 2);
        }

        public static function cacheUpdate(): void
        {
            cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN);
            cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_FRONT);
            cache()->forget(CacheKeysHelper::$HEADER_CATEGORIES_FRONT);
            cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN, function () {
                return self::whereNull('main_category')->with('translations', 'mainCategory', 'subCategories')->orderBy('position')->get();
            });

            cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_FRONT, function () {
                return self::active(true)->with('translations', 'mainCategory')
                    ->with(['subCategories' => function ($q) {
                        return $q->where('active', true)->with('translations')->with(['subCategories' => function ($qq) {
                            return $qq->where('active', true)->with('translations')->orderBy('position');
                        }])->orderBy('position');
                    }])->orderBy('position')->get();
            });

            cache()->rememberForever(CacheKeysHelper::$HEADER_CATEGORIES_FRONT, function () {
                return self::whereNull('main_category')->active(true)->with('translations', 'mainCategory')
                    ->with(['subCategories' => function ($q) {
                        return $q->where('active', true)->with('translations')->with(['subCategories' => function ($qq) {
                            return $qq->where('active', true)->with('translations')->orderBy('position');
                        }])->orderBy('position');
                    }])->orderBy('position')->get();
            });
        }

        public static function getRequestData($request): array
        {
            $data = [
                'main_category'   => $request->main_category,
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

            if ($request->hasFile('image')) {
                $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
            }

            return $data;
        }

        public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
        {
            foreach ($languages as $language) {
                $data[$language->code] = CategoryTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
            }

            return $data;
        }

        public static function generatePosition($request)
        {
            if (!isset($request->main_category)) {
                $request['main_category'] = null;
            }
            $models = self::where('main_category', $request->main_category)->orderBy('position', 'desc')->get();
            if (count($models) < 1) {
                return 1;
            }
            if (!$request->has('position') || is_null($request['position'])) {
                return $models->first()->position + 1;
            }

            if ($request['position'] > $models->first()->position) {
                return $models->first()->position + 1;
            }
            $modelsToUpdate = self::where('main_category', $request->main_category)->where('position', '>=', $request['position'])->get();
            foreach ($modelsToUpdate as $modelToUpdate) {
                $modelToUpdate->update(['position' => $modelToUpdate->position + 1]);
            }

            return $request['position'];
        }

        public function setKeys($array): array
        {
            //        Go to Shop Model
        }

        public function getFilepath($filename): string
        {
            return $this->getFilesPath() . $filename;
        }

        public function getFilesPath(): string
        {
            return self::FILES_PATH . '/' . $this->id . '/';
        }

        public function getSystemImage(): string
        {
            return AdminHelper::getSystemImage(self::$PRODUCT_CATEGORY_SYSTEM_IMAGE);
        }

        public function getEncryptedPath($moduleName): string
        {
            return encrypt($moduleName . '-' . get_class($this) . '-' . $this->id);
        }

        public function headerGallery()
        {
            return $this->getHeaderGalleryRelation(get_class($this));
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

        public function getActiveProducts(): HasMany
        {
            return $this->hasMany(Product::class, 'category_id', 'id')->where('active', true)->orderBy('position');
        }

        public function products(): HasMany
        {
            return $this->hasMany(Product::class, 'category_id', 'id');
        }

        public function mainCategory(): BelongsTo
        {
            return $this->belongsTo(Category::class, 'main_category', 'id');
        }

        public function subCategories(): HasMany
        {
            return $this->hasMany(Category::class, 'main_category');
        }

        public function getUrl($languageSlug)
        {
            return url($languageSlug . '/' . $this->translate($languageSlug)->url);
        }

        public function productAttributes(): HasManyThrough
        {
            return $this->hasManyThrough(
                ProductAttribute::class,
                ProductAttributePivot::class,
                'product_category_id',
                'id',
                'id',
                'pattr_id'
            )->orderBy('position')->with('translations');
        }

        public function updatedPosition($request)
        {
            if (!$request->has('position') || is_null($request->position) || $request->position == $this->position) {
                return $this->position;
            }
            if (!isset($request->main_category)) {
                $request['main_category'] = null;
            }
            $maxPosition = self::where('main_category', $this->main_category)->max('position');
            $minPosition = 1;

            $newPosition = max($minPosition, min($request->position, $maxPosition));
            $query       = self::where('main_category', $this->main_category)->where('id', '<>', $this->id);

            if ($newPosition > $this->position) {
                $query->whereBetween('position', [$this->position + 1, $newPosition])->decrement('position');
            } elseif ($newPosition < $this->position) {
                $query->whereBetween('position', [$newPosition, $this->position - 1])->increment('position');
            }

            $this->position = $newPosition;
            $this->save();

            return $this->position;
        }
    }

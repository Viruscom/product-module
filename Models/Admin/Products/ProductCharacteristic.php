<?php

namespace Modules\Shop\Models\Admin\Products;

use App\Helpers\AdminHelper;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\SeoHelper;
use App\Interfaces\Models\ImageModelInterface;
use App\Models\CategoryPage\CategoryPageTranslation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Modules\Shop\Entities\Settings\VatCategory;
use Modules\Shop\Models\Admin\Products\ProductAdditionalField;
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
use Illuminate\Support\Str;
use Modules\Shop\Models\Admin\Brands\Brand;
use Modules\Shop\Models\Admin\ProductCategory\Category;
use Modules\ShopDiscounts\Entities\Discount;

class ProductCharacteristic extends Model implements TranslatableContract
{
    use Translatable, StorageActions, CommonActions;

    protected $table    = 'product_characteristics';
    protected $fillable = ['position', 'active'];

    public array $translatedAttributes = ['title'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public static function cacheUpdate()
    {
        Cache::forget(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS);

        return Cache::rememberForever(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS, static function () {
            return self::orderBy('position', 'asc')->with('translations')->get();
        });
    }

    public static function getRequestData($request): array
    {
        $data = [];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = ProductCharacteristicTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
}

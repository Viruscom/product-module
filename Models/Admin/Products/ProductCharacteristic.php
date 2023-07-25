<?php

namespace Modules\Product\Models\Admin\Products;

use App\Helpers\CacheKeysHelper;
use App\Traits\CommonActions;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Admin\ProductCategory\Category;

class ProductCharacteristic extends Model implements TranslatableContract
{
    use Translatable, StorageActions, CommonActions;

    public array $translatedAttributes = ['title'];
    protected    $table                = 'product_characteristics';
    protected    $fillable             = ['position', 'active'];
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
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}

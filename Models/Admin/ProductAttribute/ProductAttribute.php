<?php

namespace Modules\Product\Models\Admin\ProductAttribute;

use App\Helpers\CacheKeysHelper;
use App\Helpers\LanguageHelper;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Product\Models\Admin\ProductAttribute\Values\ProductAttributeValue;
use Modules\Product\Models\Admin\ProductCategory\Category;

class ProductAttribute extends Model implements TranslatableContract
{
    use Translatable, Scopes, CommonActions, StorageActions;

    public array $translatedAttributes  = ['title'];
    protected    $table                 = 'product_attributes';
    protected    $fillable              = ['position', 'active', 'type'];
    protected    $translationForeignKey = 'pattr_id';
    public static function generatePosition($request): int
    {
        $cities = self::orderBy('position', 'desc')->get();
        if (count($cities) < 1) {
            return 1;
        }
        if (!$request->has('position') || is_null($request['position'])) {
            return $cities->first()->position + 1;
        }

        if ($request['position'] > $cities->first()->position) {
            return $cities->first()->position + 1;
        }
        $citiesToUpdate = self::where('position', '>=', $request['position'])->get();
        foreach ($citiesToUpdate as $cityToUpdate) {
            $cityToUpdate->update(['position' => $cityToUpdate->position + 1]);
        }

        return $request['position'];
    }
    public static function cacheUpdate()
    {
        cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN);
        cache()->forget(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_FRONT);
        cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN, function () {
            return self::with('translations')->orderBy('position')->get();
        });

        cache()->rememberForever(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_FRONT, function () {
            return self::active(true)->with('translations')->orderBy('position')->get();
        });
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = ProductAttributeTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function translations(): HasMany
    {
        return $this->hasMany(ProductAttributeTranslation::class, 'pattr_id', 'id');
    }
    public function getUpdateData($request): array
    {
        return self::getRequestData($request);
    }
    public static function getRequestData($request): array
    {
        $data = [
            'type' => $request->type
        ];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }
    public function updatedPosition($request)
    {
        if (!$request->has('position') || is_null($request->position) || $request->position == $this->position) {
            return $this->position;
        }

        $cities = self::orderBy('position', 'desc')->get();
        if (count($cities) == 1) {
            $request['position'] = 1;

            return $request['position'];
        }

        if ($request['position'] > $cities->first()->position) {
            $request['position'] = $cities->first()->position;
        } elseif ($request['position'] < $cities->last()->position) {
            $request['position'] = $cities->last()->position;
        }

        if ($request['position'] >= $this->position) {
            $citiesToUpdate = self::where('id', '<>', $this->id)->where('position', '>', $this->position)->where('position', '<=', $request['position'])->get();
            foreach ($citiesToUpdate as $cityToUpdate) {
                $cityToUpdate->update(['position' => $cityToUpdate->position - 1]);
            }

            return $request['position'];
        }

        $citiesToUpdate = self::where('id', '<>', $this->id)->where('position', '<', $this->position)->where('position', '>=', $request['position'])->get();
        foreach ($citiesToUpdate as $cityToUpdate) {
            $cityToUpdate->update(['position' => $cityToUpdate->position + 1]);
        }

        return $request['position'];
    }

    public function currentTranslation(): HasOne
    {
        $currentLanguage = LanguageHelper::getCurrentLanguage();

        return $this->hasOne(ProductAttributeTranslation::class, 'pattr_id', 'id')->where('locale', $currentLanguage->code);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attr_id', 'id')->orderBy('position', 'asc');
    }
}

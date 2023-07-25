<?php

namespace Modules\Product\Models\Admin\ProductCombination;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Models\Admin\ProductAttribute\Values\ProductAttributeValue;
use Modules\Product\Models\Admin\Products\Product;
use Modules\Product\Models\Admin\Products\ProductCharacteristicTranslation;

class ProductCombination extends Model
{
    protected static $mainProduct                 = null;
    protected static $mainProductSkuNumberCounter = 1;
    protected        $table                       = 'product_combinations';
    protected        $fillable                    = ['product_id', 'quantity', 'price', 'sku', 'combination', 'filter_combo'];
    protected        $casts                       = [
        'combination'  => 'array',
        'filter_combo' => 'array',
    ];
    public static function getRequestData($request, $combination): array
    {
        $data['product_id']   = $request->main_product_id;
        $data['combination']  = $combination;
        $data['filter_combo'] = self::generateFilterCombinationArray($combination);

        if ($request->has('quantity')) {
            $data['quantity'] = str_replace(',', '.', $request->quantity);
        }

        if ($request->has('price')) {
            $data['price'] = str_replace(',', '.', $request->price);
        }

        if ($request->has('sku')) {
            $data['sku'] = self::generateSkuNumberCombination($request);
        }

        return $data;
    }
    protected static function generateFilterCombinationArray($combination): array
    {
        unset($combination[0]);
        $filterArray = [];
        foreach ($combination as $key => $combo) {
            $attributeId               = ProductAttributeValue::select('id', 'product_attr_id')->where('id', $combo)->first()->product_attr_id;
            $filterArray[$attributeId] = $combo;
        }

        return $filterArray;
    }
    protected static function generateSkuNumberCombination($request): string
    {
        if (is_null(self::$mainProduct) || self::$mainProduct != $request->main_product_id) {
            self::$mainProduct                 = $request->main_product_id;
            self::$mainProductSkuNumberCounter = 1;
        }

        if ($request->sku != '') {
            $generatedSkuNumber = $request->sku . '-' . self::$mainProductSkuNumberCounter;
            self::$mainProductSkuNumberCounter++;

            return $generatedSkuNumber;
        }

        return '';
    }

    public function product(): BelongsTo
    {
        return $this->belongsto(Product::class, 'product_id', 'id');
    }
    public function translations(): HasMany
    {
        return $this->hasMany(ProductCharacteristicTranslation::class, 'pch_id', 'id');
    }
}

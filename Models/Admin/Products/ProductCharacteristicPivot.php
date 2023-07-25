<?php

namespace Modules\Product\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\Admin\ProductCategory\Category;

class ProductCharacteristicPivot extends Model
{
    protected $table    = 'product_characteristics_pivot';
    protected $fillable = ['product_characteristic_id', 'product_category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'product_category_id', 'id');
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(ProductCharacteristic::class, 'product_characteristic_id', 'id');
    }
}

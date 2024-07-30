<?php

namespace Modules\Product\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCharacteristicValue extends Model
{
    protected $table    = 'product_characteristic_values';
    protected $fillable = ['product_id', 'characteristic_id', 'value'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(ProductCharacteristic::class, 'characteristic_id', 'id');
    }
}

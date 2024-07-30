<?php

namespace Modules\Product\Models\Admin\ProductAttribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Models\Admin\ProductCategory\Category;

class ProductAttributePivot extends Model
{
    protected $table    = 'product_attribute_pivot';
    protected $fillable = ['pattr_id', 'product_category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'product_category_id', 'id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'pattr_id', 'id');
    }
}

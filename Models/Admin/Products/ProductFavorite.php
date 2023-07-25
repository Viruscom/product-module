<?php

namespace Modules\Shop\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;

class ProductFavorite extends Model
{
    protected $table   = "product_favorites";
    protected $guarded = ['id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(ShopRegisteredUser::class, 'user_id');
    }

}

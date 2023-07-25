<?php

namespace Modules\Shop\Models\Admin\Products;

use App\Helpers\AdminHelper;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\SeoHelper;
use App\Interfaces\Models\ImageModelInterface;
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

class ProductVatCategory extends Model
{
    protected    $table                = 'product_vat_category';
    public function product()
    {
        return $this ->belongsTo(Product::class);
    }

    public function vatCategories()
    {
        return $this->hasMany(VatCategory::class);
    }
}

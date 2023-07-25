<?php

namespace Modules\Product\Models\Admin\ProductAttribute\Values;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValueTranslation extends Model
{
    protected $table    = "product_attribute_value_translation";
    protected $fillable = ['language_id', 'pattrv_id', 'title'];

    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        return [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
        ];
    }
}

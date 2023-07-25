<?php

namespace Modules\Product\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAdditionalField extends Model
{
    public const MAX_FIELDS = 10;

    public    $timestamps = false;
    protected $table      = "product_additional_fields";
    protected $guarded    = ['id'];
    public static function getData($language, $request, $f): array
    {
        $data = [
            'locale'   => $language->code,
            'field_id' => $f,
        ];

        if ($request->has('additional_field_title_' . $f . '_' . $language->code)) {
            $data['name'] = $request['additional_field_title_' . $f . '_' . $language->code];
        }

        if ($request->has('additional_field_amount_' . $f . '_' . $language->code)) {
            $data['text'] = $request['additional_field_amount_' . $f . '_' . $language->code];
        }

        return $data;
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

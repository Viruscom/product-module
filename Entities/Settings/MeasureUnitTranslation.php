<?php

namespace Modules\Product\Entities\Settings;

use Illuminate\Database\Eloquent\Model;

class MeasureUnitTranslation extends Model
{
    protected $table    = "measure_units_translation";
    protected $fillable = ['locale', 'title'];
    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
        ];

        return $data;
    }
}

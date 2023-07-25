<?php

namespace Modules\Shop\Models\Admin\Products\Stocks;

use App\Interfaces\Models\CommonModelTranslationInterfaces;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalSupplierTranslation extends Model implements CommonModelTranslationInterfaces
{
    protected $table    = "internal_supplier_translation";
    protected $fillable = ['locale', 'internal_supplier_id', 'title', 'registration_address'];
    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
        ];

        if ($request->has('registration_address_' . $language->code)) {
            $data['registration_address'] = $request['registration_address_' . $language->code];
        }

        return $data;
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(InternalSupplier::class, 'internal_supplier_id');
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}

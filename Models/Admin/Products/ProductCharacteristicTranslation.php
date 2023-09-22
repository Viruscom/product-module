<?php

    namespace Modules\Product\Models\Admin\Products;

    use App\Interfaces\Models\CommonModelTranslationInterfaces;
    use App\Models\Language;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class ProductCharacteristicTranslation extends Model implements CommonModelTranslationInterfaces
    {
        protected $table    = "product_char_translation";
        protected $fillable = ['locale', 'product_characteristic_id', 'title'];

        public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
        {
            return [
                'locale' => $language->code,
                'title'  => $request['title_' . $language->code],
            ];
        }
        public static function createMissingLanguageRow($language, $request, $model)
        {
            $data = [
                'locale' => $language->code,
                'title'  => $request['title_' . $language->code] . '-' . $language->code,
            ];
            $model->translations()->create(self::langArray($data, $language, $request));
        }

        private static function langArray($data, $language, $request)
        {
            return $data;
        }
        public function parent(): BelongsTo
        {
            return $this->belongsTo(ProductCharacteristic::class, 'product_characteristic_id');
        }
        public function language(): BelongsTo
        {
            return $this->belongsTo(Language::class, 'language_id');
        }
    }

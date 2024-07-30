<?php

    namespace Modules\Product\Models\Admin\Brands;

    use App\Helpers\UrlHelper;
    use App\Interfaces\Models\CommonModelTranslationInterfaces;
    use App\Models\Language;
    use App\Traits\StorageActions;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class BrandTranslation extends Model implements CommonModelTranslationInterfaces
    {
        use StorageActions;

        protected $table    = "brand_translation";
        protected $fillable = ['locale', 'brand_id', 'title', 'url', 'announce', 'description', 'visible'];

        public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
        {
            $data = [
                'locale' => $language->code,
                'title'  => $request['title_' . $language->code],
            ];

            if (!$isUpdate) {
                $data['url'] = UrlHelper::generate($request['title_' . $language->code], self::class, $modelId, $isUpdate);
            }

            if ($request->has('announce_' . $language->code)) {
                $data['announce'] = $request['announce_' . $language->code];
            }

            if ($request->has('description_' . $language->code)) {
                $data['description'] = $request['description_' . $language->code];
            }

            $data['visible'] = false;
            if ($request->has('visible_' . $language->code)) {
                $data['visible'] = filter_var($request['visible_' . $language->code], FILTER_VALIDATE_BOOLEAN);
            }

            return $data;
        }

        public static function createMissingLanguageRow($language, $request, $model)
        {
            $title = $request['title_' . $language->code] . '-' . $language->code;
            $data  = [
                'locale' => $language->code,
                'title'  => $title,
                'url'    => UrlHelper::generate($title, self::class, $model->id, false)
            ];

            $model->translations()->create(self::langArray($data, $language, $request));
        }

        private static function langArray($data, $language, $request)
        {
            if ($request->has('announce_' . $language->code)) {
                $data['announce'] = $request['announce_' . $language->code];
            }

            if ($request->has('description_' . $language->code)) {
                $data['description'] = $request['description_' . $language->code];
            }

            $data['visible'] = false;
            if ($request->has('visible_' . $language->code)) {
                $data['visible'] = filter_var($request['visible_' . $language->code], FILTER_VALIDATE_BOOLEAN);
            }

            return $data;
        }

        public function parent(): BelongsTo
        {
            return $this->belongsTo(Brand::class, 'brand_id');
        }

        public function language(): BelongsTo
        {
            return $this->belongsTo(Language::class, 'language_id');
        }
    }

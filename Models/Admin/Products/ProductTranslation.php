<?php

namespace Modules\Product\Models\Admin\Products;

use App\Helpers\UrlHelper;
use App\Interfaces\Models\CommonModelTranslationInterfaces;
use App\Models\Language;
use App\Traits\StorageActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model implements CommonModelTranslationInterfaces
{
    use StorageActions;

    protected $table    = "product_translation";
    protected $fillable = ['locale', 'product_id', 'title', 'url', 'announce', 'description', 'visible', 'seo_title', 'seo_description',
                           'height', 'width', 'depth', 'weight', 'facebook_script', 'google_script', 'title_additional_1', 'title_additional_2', 'title_additional_3',
                           'title_additional_4', 'title_additional_5', 'title_additional_6', 'text_additional_1', 'text_additional_2',
                           'text_additional_3', 'text_additional_4', 'text_additional_5', 'text_additional_6'];

    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
        ];

        if (!$isUpdate) {
            $data['url'] = UrlHelper::generate($request['title_' . $language->code], self::class, $modelId, $isUpdate);
        }

        return self::langArray($data, $language, $request);
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

        if ($request->has('seo_title_' . $language->code)) {
            $data['seo_title'] = $request['seo_title_' . $language->code];
        }

        if ($request->has('seo_description_' . $language->code)) {
            $data['seo_description'] = $request['seo_description_' . $language->code];
        }

        if ($request->has('facebook_script_' . $language->code)) {
            $data['facebook_script'] = $request['facebook_script_' . $language->code];
        }

        if ($request->has('google_script_' . $language->code)) {
            $data['google_script'] = $request['google_script_' . $language->code];
        }

        if ($request->has('title_additional_1_' . $language->code)) {
            $data['title_additional_1'] = $request['title_additional_1_' . $language->code];
        }

        if ($request->has('title_additional_2_' . $language->code)) {
            $data['title_additional_2'] = $request['title_additional_2_' . $language->code];
        }

        if ($request->has('title_additional_3_' . $language->code)) {
            $data['title_additional_3'] = $request['title_additional_3_' . $language->code];
        }

        if ($request->has('title_additional_4_' . $language->code)) {
            $data['title_additional_4'] = $request['title_additional_4_' . $language->code];
        }

        if ($request->has('title_additional_5_' . $language->code)) {
            $data['title_additional_5'] = $request['title_additional_5_' . $language->code];
        }

        if ($request->has('title_additional_6_' . $language->code)) {
            $data['title_additional_6'] = $request['title_additional_6_' . $language->code];
        }

        if ($request->has('text_additional_1_' . $language->code)) {
            $data['text_additional_1'] = $request['text_additional_1_' . $language->code];
        }

        if ($request->has('text_additional_2_' . $language->code)) {
            $data['text_additional_2'] = $request['text_additional_2_' . $language->code];
        }

        if ($request->has('text_additional_3_' . $language->code)) {
            $data['text_additional_3'] = $request['text_additional_3_' . $language->code];
        }

        if ($request->has('text_additional_4_' . $language->code)) {
            $data['text_additional_4'] = $request['text_additional_4_' . $language->code];
        }

        if ($request->has('text_additional_5_' . $language->code)) {
            $data['text_additional_5'] = $request['text_additional_5_' . $language->code];
        }

        if ($request->has('text_additional_6_' . $language->code)) {
            $data['text_additional_6'] = $request['text_additional_6_' . $language->code];
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
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}

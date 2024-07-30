<?php

    namespace Modules\Product\Models\Admin\ProductAttribute\Values;

    use App\Helpers\AdminHelper;
    use App\Helpers\FileDimensionHelper;
    use App\Traits\CommonActions;
    use App\Traits\Scopes;
    use App\Traits\StorageActions;
    use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
    use Astrotomic\Translatable\Translatable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttribute;

    class ProductAttributeValue extends Model implements TranslatableContract
    {
        use Translatable, Scopes, CommonActions, StorageActions;

        public const FILES_PATH = "images/products/attribute_values";
        public static string $PRODUCT_ATTRIBUTE_VALUE_SYSTEM_IMAGE  = 'product_attribute_value_img.png';
        public static string $PRODUCT_ATTRIBUTE_VALUE_RATIO         = '1/1';
        public static string $PRODUCT_ATTRIBUTE_VALUE_MIMES         = 'jpg,jpeg,png,gif,webp';
        public static string $PRODUCT_ATTRIBUTE_VALUE_MAX_FILE_SIZE = '3000';

        public array $translatedAttributes  = ['title'];
        protected    $table                 = 'product_attribute_values';
        protected    $fillable              = ['product_attr_id', 'color_picker_color', 'filename', 'position'];
        protected    $translationForeignKey = 'pattrv_id';

        public static function getFileRules(): string
        {
            return FileDimensionHelper::getRules('Product', 3);
        }

        public static function getUserInfoMessage(): string
        {
            return FileDimensionHelper::getUserInfoMessage('Product', 3);
        }

        public static function generatePosition($request): int
        {
            $cities = self::orderBy('position', 'desc')->get();
            if (count($cities) < 1) {
                return 1;
            }
            if (!$request->has('position') || is_null($request['position'])) {
                return $cities->first()->position + 1;
            }

            if ($request['position'] > $cities->first()->position) {
                return $cities->first()->position + 1;
            }
            $citiesToUpdate = self::where('position', '>=', $request['position'])->get();
            foreach ($citiesToUpdate as $cityToUpdate) {
                $cityToUpdate->update(['position' => $cityToUpdate->position + 1]);
            }

            return $request['position'];
        }

        public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
        {
            foreach ($languages as $language) {
                $data[$language->code] = ProductAttributeValueTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
            }

            return $data;
        }

        public static function getCreateInputErrors($languages, $request, $attr_id): array
        {
            $errors = [];
            foreach ($languages as $language) {
                $langTitle = 'title_' . $language->code;
                if (!$request->has($langTitle) || $request[$langTitle] == "") {
                    $errors[$langTitle] = 'admin.common.title_exists';
                } else {
                    $productAttributeValue = self::where('product_attr_id', $attr_id)->with('translations')->get();
                    if (!is_null($productAttributeValue)) {
                        foreach ($productAttributeValue as $value) {
                            $langTitleRow = $value->translations()->where('locale', $language->code)->where('title', $request->{$langTitle})->first();
                            if (!is_null($langTitleRow)) {
                                $errors[$langTitle] = 'admin.common.title_exists';
                            }
                        }
                    }
                }
            }

            return $errors;
        }

        public function translations(): HasMany
        {
            return $this->hasMany(ProductAttributeValueTranslation::class, 'pattrv_id', 'id');
        }

        public static function cacheUpdate()
        {
            //Prevent error
        }

        public function parent(): BelongsTo
        {
            return $this->belongsTo(ProductAttribute::class, 'product_attr_id');
        }

        public function getUpdateData($request): array
        {
            return self::getRequestData($request);
        }

        public static function getRequestData($request): array
        {
            $data = [
                'type'            => $request->type,
                'product_attr_id' => $request->product_attr_id,
            ];

            $data['active'] = false;
            if ($request->has('active')) {
                $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('color_picker_color')) {
                $data['color_picker_color'] = $request->color_picker_color;
            }

            if ($request->hasFile('image')) {
                $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
            }

            return $data;
        }

        public function updatedPosition($request)
        {
            if (!$request->has('position') || is_null($request->position) || $request->position == $this->position) {
                return $this->position;
            }

            $cities = self::where('product_attr_id', $request->product_attr_id)->orderBy('position', 'asc')->get();

            if (count($cities) == 1) {
                $request['position'] = 1;
            } elseif ($request['position'] > $cities->last()->position) {
                $request['position'] = $cities->last()->position;
            } elseif ($request['position'] < $cities->first()->position) {
                $request['position'] = $cities->first()->position;
            }

            if ($request['position'] > $this->position) {
                $citiesToUpdate = self::where('product_attr_id', $request->product_attr_id)->where('id', '<>', $this->id)
                    ->where('position', '<=', $request['position'])->where('position', '>', $this->position)->get();
                self::updateModelsPosition($citiesToUpdate, false);
            } else {
                $citiesToUpdate = self::where('product_attr_id', $request->product_attr_id)->where('id', '<>', $this->id)
                    ->where('position', '>=', $request['position'])->where('position', '<', $this->position)->get();
                self::updateModelsPosition($citiesToUpdate, true);
            }

            $this->update(['position' => $request['position']]);

            return $request['position'];
        }

        private static function updateModelsPosition($models, $increment)
        {
            $operator = $increment ? 1 : -1;
            foreach ($models as $model) {
                $model->increment('position', $operator);
            }
        }


        public function getUpdateInputErrors($languages, $request): array
        {
            $errors = [];
            foreach ($languages as $language) {
                $langTitle = 'title_' . $language->code;
                if (!$request->has($langTitle) || $request[$langTitle] == "") {
                    $errors[$langTitle] = 'administration_messages.title_required';
                } else {
                    $productAttributeValues = $this->parent->values;
                    foreach ($productAttributeValues as $value) {
                        if ($value->id != $this->id) {
                            $langTitleRow = $value->translations()->where('locale', $language->code)->where('title', $request->{$langTitle})->first();
                            if (!is_null($langTitleRow)) {
                                $errors[$langTitle] = 'administration_messages.title_exists';
                            }
                        }
                    }
                }
            }

            return $errors;
        }

        public function getFilepath($filename): string
        {
            return $this->getFilesPath() . $filename;
        }

        public function getFilesPath(): string
        {
            return self::FILES_PATH . '/' . $this->id . '/';
        }

        public function getSystemImage(): string
        {
            return AdminHelper::getSystemImage(self::$PRODUCT_ATTRIBUTE_VALUE_SYSTEM_IMAGE);
        }
    }

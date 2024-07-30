<?php

    namespace Modules\Product\Actions;

    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Models\Files\File;
    use Cache;
    use Illuminate\Http\Request;
    use Modules\AdBoxes\Models\AdBox;
    use Modules\Product\Entities\Settings\MeasureUnit;
    use Modules\Product\Models\Admin\Brands\Brand;
    use Modules\Product\Models\Admin\ProductCategory\Category;
    use Modules\Product\Models\Admin\Products\ProductAdditionalField;

    class ProductAction
    {
        public function checkForFilesCache(): void
        {
            if (is_null(Cache::get(CacheKeysHelper::$FILES))) {
                File::updateCache();
            }
        }

        public function checkForProductCategoriesAdminCache(): void
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }
        }

        public function checkForBrandsCache(): void
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_BRAND_ADMIN))) {
                Brand::cacheUpdate();
            }
        }

        public function checkForMeasureUnitsCache(): void
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN))) {
                MeasureUnit::cacheUpdate();
            }
        }

        public function createOrUpdateAdditionalFields(Request $request, $product)
        {
            $languages = LanguageHelper::getActiveLanguages();

            $maxFields = ProductAdditionalField::MAX_FIELDS;

            foreach ($languages as $language) {
                for ($f = 1; $f <= $maxFields; $f++) {
                    $data = ProductAdditionalField::getData($language, $request, $f);

                    $additionalField = ProductAdditionalField::updateOrCreate(
                        ['product_id' => $product->id, 'locale' => $data['locale'], 'field_id' => $data['field_id']],
                        ['name' => $data['name'], 'text' => $data['text']]
                    );

                    $product->additionalFields()->save($additionalField);
                }
            }
        }

        public function sendToAdBox($product): void
        {
            $languages = LanguageHelper::getActiveLanguages();
            $data      = new Request();
            foreach ($languages as $language) {
                $productTitle          = is_null($product->translate($language->code)) ? $product->title : $product->translate($language->code)->title;
                $productUrl            = is_null($product->translate($language->code)) ? $product->url : $product->translate($language->code)->url;
                $productAnnounce       = is_null($product->translate($language->code)) ? $product->announce : $product->translate($language->code)->announce;
                $data[$language->code] = [
                    'locale'            => $language->code,
                    'title'             => $productTitle,
                    'short_description' => $productAnnounce,
                    'url'               => $productUrl,
                    'visible'           => true
                ];
            }
            $data['type']           = AdBox::$WAITING_ACTION;
            $data['position']       = AdBox::generatePositionForWaitingAdBox();
            $data['active']         = true;
            $data['from_price']     = $product->catalog_from_price;
            $data['price']          = $product->price == '' || $product->price == null ? '0.00' : $product->price;
            $data['from_new_price'] = $product->catalog_from_discounted_price;
            $data['new_price']      = $product->catalog_discounted_price == '' || $product->catalog_discounted_price == null ? '0.00' : $product->catalog_discounted_price;

            AdBox::create($data->all());
            AdBox::cacheUpdate();
        }

        public function positionUp($modelClass, $model)
        {
            $previousModel = $modelClass::where('category_id', $model->category_id)->where('position', $model->position - 1)->first();
            if (!is_null($previousModel)) {
                $previousModel->update(['position' => $previousModel->position + 1]);
                $model->update(['position' => $model->position - 1]);
            }
        }

        public function positionDown($modelClass, $model)
        {
            $nextModel = $modelClass::where('category_id', $model->category_id)->where('position', $model->position + 1)->first();
            if (!is_null($nextModel)) {
                $nextModel->update(['position' => $nextModel->position - 1]);
                $model->update(['position' => $model->position + 1]);
            }
        }
    }

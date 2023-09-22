<?php

    namespace Modules\Product\Http\Controllers\admin\ProductAttributes;

    use App\Actions\CommonControllerAction;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Modules\Product\Http\Requests\ProductAttributeValueStoreRequest;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttribute;
    use Modules\Product\Models\Admin\ProductAttribute\Values\ProductAttributeValue;
    use Modules\Product\Models\Admin\ProductAttribute\Values\ProductAttributeValueTranslation;
    use Modules\Product\Models\Admin\ProductCategory\Category;

    class ProductAttributeValuesController extends Controller
    {
        public function index($id)
        {
            $productAttribute = ProductAttribute::where('id', $id)->with('values')->first();
            MainHelper::goBackIfNull($productAttribute);

            //        dd($productAttribute->values);

            return view('product::admin.product_attributes.values.index', ['productAttribute' => $productAttribute]);
        }
        public function store($id, ProductAttributeValueStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = ProductAttribute::where('id', $id)->first();
            MainHelper::goBackIfNull($productAttribute);

            $languages = LanguageHelper::getActiveLanguages();
            $errors    = ProductAttributeValue::getCreateInputErrors($languages, $request, $productAttribute->id);
            if (count($errors) > 0) {
                return redirect()->back()->withInput()->withErrors($errors);
            }

            $request['product_attr_id'] = $productAttribute->id;
            $value                      = $action->doSimpleCreate(ProductAttributeValue::class, $request);

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.product-attribute.values.index', ['id' => $productAttribute->id])->with('success-message', trans('admin.common.successful_create'));
        }
        public function edit($id, $value_id)
        {
            $productAttribute = ProductAttribute::where('id', $id)->with('values')->first();
            MainHelper::goBackIfNull($productAttribute);

            $ProductAttributeValue = ProductAttributeValue::find($value_id);
            MainHelper::goBackIfNull($ProductAttributeValue);

            return view('product::admin.product_attributes.values.edit', [
                'productAttribute'      => $productAttribute,
                'productAttributeValue' => $ProductAttributeValue,
                'languages'             => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo'         => ''
            ]);
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $ids = array_map('intval', explode(',', $request->ids[0]));
                foreach ($ids as $id) {
                    $ProductAttributeValue = ProductAttributeValue::find($id);
                    if (is_null($ProductAttributeValue)) {
                        continue;
                    }

                    $valuesToUpdate = ProductAttributeValue::where('product_attr_id', $ProductAttributeValue->parent->id)->where('position', '>', $ProductAttributeValue->position)->get();
                    //TODO: Delete all product combinations with that attribute value
                    if ($ProductAttributeValue->existsFile($ProductAttributeValue->filename)) {
                        $ProductAttributeValue->deleteFile($ProductAttributeValue->filename);
                    }
                    $ProductAttributeValue->delete();
                    foreach ($valuesToUpdate as $ProductAttributeValueToUpdate) {
                        $ProductAttributeValueToUpdate->update(['position' => $ProductAttributeValueToUpdate->position - 1]);
                    }
                }

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function delete($id, $value_id, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            MainHelper::goBackIfNull($productAttribute);

            $ProductAttributeValue = ProductAttributeValue::find($value_id);
            MainHelper::goBackIfNull($ProductAttributeValue);

            $valuesToUpdate = ProductAttributeValue::where('product_attr_id', $productAttribute->id)->where('position', '>', $ProductAttributeValue->position)->get();
            //TODO: Delete all product combinations with that attribute value
            if ($ProductAttributeValue->existsFile($ProductAttributeValue->filename)) {
                $ProductAttributeValue->deleteFile($ProductAttributeValue->filename);
            }
            $ProductAttributeValue->delete();
            foreach ($valuesToUpdate as $ProductAttributeValueToUpdate) {
                $ProductAttributeValueToUpdate->update(['position' => $ProductAttributeValueToUpdate->position - 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        public function update($id, $value_id, ProductAttributeValueStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = ProductAttribute::where('id', $id)->with('values', 'values.translations')->first();
            MainHelper::goBackIfNull($productAttribute);

            $productAttributeValue = ProductAttributeValue::where('id', $value_id)->with('parent')->first();
            MainHelper::goBackIfNull($productAttributeValue);

            $languages = LanguageHelper::getActiveLanguages();
            $errors    = $productAttributeValue->getUpdateInputErrors($languages, $request);
            if (count($errors) > 0) {
                return redirect()->back()->withInput()->withErrors($errors);
            }

            $request['product_attr_id'] = $productAttribute->id;
            $request['position']        = $productAttributeValue->updatedPosition($request);

            if ($request->has('image')) {
                $productAttributeValue->saveFile($request->image);
            }
            $action->doSimpleUpdate(ProductAttributeValue::class, ProductAttributeValueTranslation::class, $productAttributeValue, $request);

            return redirect()->route('admin.product-attribute.values.index', ['id' => $productAttribute->id])->with('success-message', 'admin.common.successful_edit');
        }
        public function create($id)
        {
            $productAttribute = ProductAttribute::where('id', $id)->with('values')->first();
            MainHelper::goBackIfNull($productAttribute);

            return view('product::admin.product_attributes.values.create', [
                'productAttribute' => $productAttribute,
                'languages'        => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo'    => ''
            ]);
        }
        public function positionUp($id, $value_id): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            MainHelper::goBackIfNull($productAttribute);

            $ProductAttributeValue = ProductAttributeValue::find($value_id);
            MainHelper::goBackIfNull($ProductAttributeValue);

            $nextValue = ProductAttributeValue::where('product_attr_id', $productAttribute->id)->where('position', $ProductAttributeValue->position - 1)->first();
            if (!is_null($nextValue)) {
                $nextValue->update(['position' => $nextValue->position + 1]);
                $ProductAttributeValue->update(['position' => $ProductAttributeValue->position - 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function positionDown($id, $value_id): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            MainHelper::goBackIfNull($productAttribute);

            $ProductAttributeValue = ProductAttributeValue::find($value_id);
            MainHelper::goBackIfNull($ProductAttributeValue);

            $nextValue = ProductAttributeValue::where('product_attr_id', $productAttribute->id)->where('position', $ProductAttributeValue->position + 1)->first();
            if (!is_null($nextValue)) {
                $nextValue->update(['position' => $nextValue->position - 1]);
                $ProductAttributeValue->update(['position' => $ProductAttributeValue->position + 1]);
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function deleteImage($id, $value_id, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            MainHelper::goBackIfNull($productAttribute);

            $ProductAttributeValue = ProductAttributeValue::find($value_id);
            MainHelper::goBackIfNull($ProductAttributeValue);

            if ($action->imageDelete($ProductAttributeValue, ProductAttributeValue::class)) {
                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.image_not_found']);
        }

        public function subCategoriesIndex($id)
        {
            $productCategory = Category::find($id);
            MainHelper::goBackIfNull($productCategory);

            $categories = Category::where('main_category', $productCategory->id)->with('translations')->orderBy('position')->get();

            return view('product::admin.product_categories.index', ['categories' => $categories, 'mainCategory' => $productCategory]);
        }

        public function subCategoriesCreate($id)
        {
            $productCategory = Category::find($id);
            MainHelper::goBackIfNull($productCategory);

            return view('product::admin.product_categories.create', [
                'languages'     => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo' => Category::getUserInfoMessage(),
                'categories'    => Category::where('main_category', $productCategory->id)->with('translations')->orderBy('position')->get(),
                'mainCategory'  => $productCategory
            ]);
        }
    }

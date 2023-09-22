<?php

    namespace Modules\Product\Http\Controllers\admin\ProductAttributes;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Helpers\WebsiteHelper;
    use App\Http\Controllers\Controller;
    use App\Interfaces\PositionInterface;
    use App\Models\ProductCategory;
    use Cache;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Modules\Product\Http\Requests\ProductAttributeStoreRequest;
    use Modules\Product\Http\Requests\ProductAttributeUpdateRequest;
    use Modules\Product\Interfaces\ShopProductAttributeInterface;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttribute;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttributePivot;
    use Modules\Product\Models\Admin\ProductAttribute\ProductAttributeTranslation;
    use Modules\Product\Models\Admin\ProductCategory\Category;

    class ProductAttributesController extends Controller implements ShopProductAttributeInterface, PositionInterface
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN))) {
                ProductAttribute::cacheUpdate();
            }

            return view('product::admin.product_attributes.index', ['productAttributes' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN)]);
        }
        public function store(ProductAttributeStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = $action->doSimpleCreate(ProductAttribute::class, $request);

            if ($request->has('productCategories')) {
                foreach ($request->productCategories as $key => $productCategoryId) {
                    ProductAttributePivot::create([
                                                      'pattr_id'            => $productAttribute->id,
                                                      'product_category_id' => $productCategoryId
                                                  ]);
                }
            }

            ProductAttribute::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.product-attributes.index')->with('success-message', trans('admin.common.successful_create'));
        }
        public function create()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN))) {
                ProductAttribute::cacheUpdate();
            }
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }

            return view('product::admin.product_attributes.create', [
                'languages'         => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo'     => Category::getUserInfoMessage(),
                'productCategories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN),
                'characteristics'   => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN)
            ]);
        }
        public function edit($id)
        {
            $productAttribute = ProductAttribute::where('id', $id)->with('translations')->first();
            WebsiteHelper::redirectBackIfNull($productAttribute);

            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN))) {
                ProductAttribute::cacheUpdate();
            }
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }

            return view('product::admin.product_attributes.edit', [
                'productAttribute'          => $productAttribute,
                'languages'                 => LanguageHelper::getActiveLanguages(),
                'productCategories'         => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN),
                'characteristics'           => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_ATTRIBUTES_ADMIN),
                'selectedProductCategories' => Arr::flatten(ProductAttributePivot::select('product_category_id')->where('pattr_id', $productAttribute->id)->get()->toArray())
            ]);
        }
        public function positionUp($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductAttribute::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->positionUp(ProductAttribute::class, $productCharacteristic);
            ProductAttribute::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionDown($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductAttribute::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->positionDown(ProductAttribute::class, $productCharacteristic);
            ProductAttribute::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $action->deleteMultiple($request, ProductAttribute::class);

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(ProductAttribute::class, $request, $active);
            ProductAttribute::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function active($id, $active): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            MainHelper::goBackIfNull($productAttribute);

            $productAttribute->update(['active' => $active]);
            ProductAttribute::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function update($id, ProductAttributeUpdateRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productAttribute = ProductAttribute::find($id);
            if (is_null($productAttribute)) {
                return redirect()->back()->withInput()->withErrors(['admin.common.record_not_found']);
            }

            //        $request['position'] = $productCharacteristic->updatedPosition($request);
            $action->doSimpleUpdate(ProductAttribute::class, ProductAttributeTranslation::class, $productAttribute, $request);

            if ($request->has('productCategories')) {
                ProductAttributePivot::where('pattr_id', $productAttribute->id)->delete();
                foreach ($request->productCategories as $key => $productCategoryId) {
                    ProductAttributePivot::create([
                                                      'pattr_id'            => $productAttribute->id,
                                                      'product_category_id' => $productCategoryId
                                                  ]);
                }
            }

            ProductAttribute::cacheUpdate();

            return redirect()->route('admin.product-attributes.index')->with('success-message', 'admin.common.successful_edit');
        }
        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductAttribute::where('id', $id)->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->delete(ProductAttribute::class, $productCharacteristic);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
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

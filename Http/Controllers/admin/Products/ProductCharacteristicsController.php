<?php

    namespace Modules\Product\Http\Controllers\admin\Products;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Helpers\WebsiteHelper;
    use App\Http\Controllers\Controller;
    use App\Interfaces\PositionInterface;
    use Cache;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Modules\Product\Http\Requests\ProductCharacteristicRequest;
    use Modules\Product\Models\Admin\ProductCategory\Category;
    use Modules\Product\Models\Admin\Products\Product;
    use Modules\Product\Models\Admin\Products\ProductCharacteristic;
    use Modules\Product\Models\Admin\Products\ProductCharacteristicPivot;
    use Modules\Product\Models\Admin\Products\ProductCharacteristicTranslation;
    use Modules\Product\Models\Admin\Products\ProductCharacteristicValue;

    class ProductCharacteristicsController extends Controller implements PositionInterface
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS))) {
                ProductCharacteristic::cacheUpdate();
            }

            return view('product::admin.products.characteristics.index', ['characteristics' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS)]);
        }
        public function store(ProductCharacteristicRequest $request, CommonControllerAction $action)
        {
            $productCharacteristic = $action->doSimpleCreate(ProductCharacteristic::class, $request);

            if ($request->has('productCategories')) {
                foreach ($request->productCategories as $key => $productCategoryId) {
                    ProductCharacteristicPivot::create([
                                                           'product_characteristic_id' => $productCharacteristic->id,
                                                           'product_category_id'       => $productCategoryId
                                                       ]);
                }
            }

            ProductCharacteristic::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.product_characteristics.index')->with('success-message', trans('admin.common.successful_create'));
        }
        public function create()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS))) {
                ProductCharacteristic::cacheUpdate();
            }
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }

            return view('product::admin.products.characteristics.create', [
                'languages'         => LanguageHelper::getActiveLanguages(),
                'characteristics'   => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS),
                'productCategories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN)
            ]);
        }
        public function edit($id)
        {
            $productCharacteristic = ProductCharacteristic::where('id', $id)->with('translations')->first();
            WebsiteHelper::redirectBackIfNull($productCharacteristic);

            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CHARACTERISTICS))) {
                ProductCharacteristic::cacheUpdate();
            }

            return view('product::admin.products.characteristics.edit', [
                'productCharacteristic'     => $productCharacteristic,
                'languages'                 => LanguageHelper::getActiveLanguages(),
                'characteristics'           => Cache::get('adminProductCharacteristics'),
                'productCategories'         => Category::with('translations')->get(),
                'selectedProductCategories' => Arr::flatten(ProductCharacteristicPivot::select('product_category_id')->where('product_characteristic_id', $productCharacteristic->id)->get()->toArray())
            ]);
        }
        public function positionUp($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductCharacteristic::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->positionUp(ProductCharacteristic::class, $productCharacteristic);
            ProductCharacteristic::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionDown($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductCharacteristic::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->positionDown(ProductCharacteristic::class, $productCharacteristic);
            ProductCharacteristic::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $action->deleteMultiple($request, ProductCharacteristic::class);

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(ProductCharacteristic::class, $request, $active);
            ProductCharacteristic::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function active($id, $active): RedirectResponse
        {
            $productCharacteristic = ProductCharacteristic::find($id);
            MainHelper::goBackIfNull($productCharacteristic);

            $productCharacteristic->update(['active' => $active]);
            ProductCharacteristic::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function update($id, ProductCharacteristicRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductCharacteristic::find($id);
            if (is_null($productCharacteristic)) {
                return redirect()->back()->withInput()->withErrors(['admin.common.record_not_found']);
            }

            //        $request['position'] = $productCharacteristic->updatedPosition($request);
            $action->doSimpleUpdate(ProductCharacteristic::class, ProductCharacteristicTranslation::class, $productCharacteristic, $request);

            if ($request->has('productCategories')) {
                ProductCharacteristicPivot::where('product_characteristic_id', $productCharacteristic->id)->delete();
                foreach ($request->productCategories as $key => $productCategoryId) {
                    ProductCharacteristicPivot::create([
                                                           'product_characteristic_id' => $productCharacteristic->id,
                                                           'product_category_id'       => $productCategoryId
                                                       ]);
                }
            }

            ProductCharacteristic::cacheUpdate();

            return redirect()->route('admin.product_characteristics.index')->with('success-message', 'admin.common.successful_edit');
        }
        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $productCharacteristic = ProductCharacteristic::where('id', $id)->first();
            MainHelper::goBackIfNull($productCharacteristic);

            $action->delete(ProductCharacteristic::class, $productCharacteristic);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        public function characteristicsByProductId($id)
        {
            $mainProduct = Product::find($id);
            WebsiteHelper::redirectBackIfNull($mainProduct);

            $characteristics = ProductCharacteristicPivot::where('product_category_id', $mainProduct->category_id)
                ->with('characteristic', 'characteristic.translations')
                ->get();
            $characteristics->map(function ($item) use ($mainProduct) {
                $item['value'] = ProductCharacteristicValue::select('value')->where('product_id', $mainProduct->id)->where('characteristic_id', $item->product_characteristic_id)->first();

                return $item;
            });

            return view('product::admin.products.characteristics.editPerProduct', compact('characteristics', 'mainProduct'));
        }

        public function characteristicsByProductIdUpdate($id, Request $request)
        {
            $mainProduct = Product::find($id);
            WebsiteHelper::redirectBackIfNull($mainProduct);

            foreach ($request->characteristicValue as $characteristicId => $value) {
                ProductCharacteristicValue::updateOrCreate(
                    ['product_id' => $mainProduct->id, 'characteristic_id' => $characteristicId],
                    ['value' => $value]
                );
            }
            $characteristics = ProductCharacteristicPivot::where('product_category_id', $mainProduct->category_id)
                ->with('characteristic', 'characteristic.translations')
                ->get();
            $characteristics->map(function ($item) use ($mainProduct) {
                $item['value'] = ProductCharacteristicValue::select('value')->where('product_id', $mainProduct->id)->where('characteristic_id', $item->product_characteristic_id)->first();

                return $item;
            });

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
    }

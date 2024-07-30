<?php

    namespace Modules\Product\Http\Controllers\admin\Products;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Helpers\ModuleHelper;
    use App\Http\Controllers\Controller;
    use App\Models\Files\File;
    use Cache;
    use Illuminate\Contracts\Support\Renderable;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Modules\Catalogs\Models\MainCatalog;
    use Modules\Product\Actions\ProductAction;
    use Modules\Product\Http\Requests\ProductStoreRequest;
    use Modules\Product\Http\Requests\ProductUpdateRequest;
    use Modules\Product\Interfaces\ShopProductInterface;
    use Modules\Product\Models\Admin\ProductCategory\Category;
    use Modules\Product\Models\Admin\Products\Product;
    use Modules\Product\Models\Admin\Products\ProductTranslation;

    class ProductsController extends Controller implements ShopProductInterface
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }

            return view('product::admin.products.categories', ['categories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN)]);
        }

        public function store(ProductStoreRequest $request, CommonControllerAction $action, ProductAction $productAction): RedirectResponse
        {
            $action->validateImage($request, 'Product', 3);
            $product = $action->doSimpleCreate(Product::class, $request);
            $action->updateUrlCache($product, ProductTranslation::class);
            $action->storeSeo($request, $product, 'Product');
            $productAction->createOrUpdateAdditionalFields($request, $product);

            Product::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.products.index_by_category', ['category_id' => $product->category->id])->with('success-message', trans('admin.common.successful_create'));
        }

        public function create($category_id, ProductAction $action): Renderable
        {
            $productCategory = Category::where('id', $category_id)->with(['products' => function ($query) {
                $query->with('translations')->orderBy('position');
            }])->first();
            MainHelper::goBackIfNull($productCategory);

            $action->checkForFilesCache();
            $action->checkForBrandsCache();
            $action->checkForProductCategoriesAdminCache();
            $action->checkForMeasureUnitsCache();

            $data = [
                'languages'         => LanguageHelper::getActiveLanguages(),
                'files'             => Cache::get(CacheKeysHelper::$FILES),
                'filesPathUrl'      => File::getFilesPathUrl(),
                'fileRulesInfo'     => Product::getUserInfoMessage(),
                'productCategoryId' => $productCategory->id,
                'products'          => $productCategory->products,
                'productCategories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN),
                'brands'            => Cache::get(CacheKeysHelper::$SHOP_BRAND_ADMIN),
                'measureUnits'      => Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN),
            ];

            $activeModules = ModuleHelper::getActiveModules();
            if (array_key_exists('Catalogs', $activeModules)) {
                if (is_null(CacheKeysHelper::$CATALOGS_MAIN_FRONT)) {
                    MainCatalog::cacheUpdate();
                }
                $data['mainCatalogs'] = cache()->get(CacheKeysHelper::$CATALOGS_MAIN_FRONT);
            }

            return view('product::admin.products.create', $data);
        }

        public function edit($id, ProductAction $action)
        {
            $action->checkForFilesCache();
            $action->checkForBrandsCache();
            $action->checkForProductCategoriesAdminCache();
            $action->checkForMeasureUnitsCache();

            $product = Product::whereId($id)->with('translations', 'category', 'category.products')->first();
            MainHelper::goBackIfNull($product);

            $data = [
                'product'           => $product,
                'products'          => $product->category->products()->orderBy('position')->get(),
                'languages'         => LanguageHelper::getActiveLanguages(),
                'files'             => Cache::get(CacheKeysHelper::$FILES),
                'filesPathUrl'      => File::getFilesPathUrl(),
                'fileRulesInfo'     => Product::getUserInfoMessage(),
                'productCategoryId' => $product->category->id,
                'productCategories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN),
                'brands'            => Cache::get(CacheKeysHelper::$SHOP_BRAND_ADMIN),
                'measureUnits'      => Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN),
            ];

            $activeModules = ModuleHelper::getActiveModules();
            if (array_key_exists('Catalogs', $activeModules)) {
                if (is_null(CacheKeysHelper::$CATALOGS_MAIN_FRONT)) {
                    MainCatalog::cacheUpdate();
                }
                $data['mainCatalogs'] = cache()->get(CacheKeysHelper::$CATALOGS_MAIN_FRONT);
            }

            return view('product::admin.products.edit', $data);
        }

        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $action->deleteMultiple($request, Product::class);

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }

        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $product = Product::find($id);
            MainHelper::goBackIfNull($product);

            $action->deleteFromUrlCache($product);
            $action->delete(Product::class, $product);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(Product::class, $request, $active);
            Product::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function active($id, $active): RedirectResponse
        {
            $product = Product::find($id);
            MainHelper::goBackIfNull($product);

            $product->update(['active' => $active]);
            Product::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function update($id, ProductUpdateRequest $request, CommonControllerAction $action, ProductAction $productAction): RedirectResponse
        {
            $product = Product::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($product);

            $action->validateImage($request, 'Product', 3);
            $action->doSimpleUpdate(Product::class, ProductTranslation::class, $product, $request);
            $action->updateUrlCache($product, ProductTranslation::class);
            $action->updateSeo($request, $product, 'Product');
            $productAction->createOrUpdateAdditionalFields($request, $product);

            if ($request->has('image')) {
                $product->saveFile($request->image);
            }

            Product::cacheUpdate();

            return redirect()->route('admin.products.index_by_category', ['category_id' => $product->category->id])->with('success-message', 'admin.common.successful_edit');
        }

        public function positionUp($id, ProductAction $productAction): RedirectResponse
        {
            $product = Product::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($product);

            $productAction->positionUp(Product::class, $product);
            Product::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function positionDown($id, ProductAction $productAction): RedirectResponse
        {
            $product = Product::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($product);

            $productAction->positionDown(Product::class, $product);
            Product::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function deleteImage($id, CommonControllerAction $action): RedirectResponse
        {
            $product = Product::find($id);
            MainHelper::goBackIfNull($product);

            if ($action->imageDelete($product, Product::class)) {
                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.image_not_found']);
        }

        public function makeProductAdBox($id, ProductAction $action)
        {
            $product = Product::find($id);
            MainHelper::goBackIfNull($product);

            if ($action->isProductAdBoxExists($product->id)) {
                return redirect()->back()->withErrors(['product::admin.product_adboxes.product_ad_box_already_exists']);
            }

            $action->sendToProductAdbox($product->id);

            return redirect()->back()->with('success-message', trans('admin.common.successful_create'));
        }

        public function makeAdBox($id, ProductAction $action)
        {
            $product = Product::find($id);
            MainHelper::goBackIfNull($product);

            $action->sendToAdBox($product);

            return redirect()->back()->with('success-message', trans('admin.common.successful_create'));
        }

        public function getCategoryProducts($category_id)
        {
            $productCategory = Category::where('id', $category_id)->with(['products' => function ($query) {
                $query->with('translations')->orderBy('position');
            }])->first();
            MainHelper::goBackIfNull($productCategory);

            return view('product::admin.products.index', [
                'productCategory' => $productCategory,
                'products'        => $productCategory->products
            ]);
        }
    }

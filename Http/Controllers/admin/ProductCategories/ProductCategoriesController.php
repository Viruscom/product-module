<?php

    namespace Modules\Product\Http\Controllers\admin\ProductCategories;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Http\Controllers\Controller;
    use App\Interfaces\PositionInterface;
    use Cache;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Modules\Product\Http\Requests\ProductCategoryStoreRequest;
    use Modules\Product\Http\Requests\ProductCategoryUpdateRequest;
    use Modules\Product\Interfaces\ShopProductCategoryInterface;
    use Modules\Product\Models\Admin\ProductCategory\Category;
    use Modules\Product\Models\Admin\ProductCategory\CategoryTranslation;

    class ProductCategoriesController extends Controller implements ShopProductCategoryInterface, PositionInterface
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN))) {
                Category::cacheUpdate();
            }

            return view('product::admin.product_categories.index', ['categories' => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN)]);
        }
        public function store(ProductCategoryStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $action->validateImage($request, 'Product', 2);
            $productCategory = $action->doSimpleCreate(Category::class, $request);
            $action->updateUrlCache($productCategory, CategoryTranslation::class);
            $action->storeSeo($request, $productCategory, 'Category');
            Category::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            if (!is_null($productCategory->main_category)) {
                return redirect()->route('admin.product-categories.sub-categories.index', ['id' => $productCategory->main_category])->with('success-message', trans('admin.common.successful_create'));
            }

            return redirect()->route('admin.product-categories.index')->with('success-message', trans('admin.common.successful_create'));
        }
        public function create()
        {
            return view('product::admin.product_categories.create', [
                'languages'     => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo' => Category::getUserInfoMessage(),
                'categories'    => Cache::get(CacheKeysHelper::$SHOP_PRODUCT_CATEGORY_ADMIN)
            ]);
        }
        public function edit($id)
        {
            $productCategory = Category::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCategory);

            return view('product::admin.product_categories.edit', [
                'category'      => $productCategory,
                'categories'    => Category::where('main_category', $productCategory->main_category)->with('translations')->orderBy('position')->get(),
                'languages'     => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo' => Category::getUserInfoMessage()
            ]);
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $ids = array_map('intval', explode(',', $request->ids[0]));
                foreach ($ids as $id) {
                    $model = Category::find($id);
                    if (is_null($model)) {
                        continue;
                    }

                    if ($model->subCategories->isNotEmpty()) {
                        return back()->withErrors(['product::admin.product_categories.error_cant_delete_has_sub_categories']);
                    }
                    if ($model->products->isNotEmpty()) {
                        return back()->withErrors(['product::admin.product_categories.error_cant_delete_has_products']);
                    }

                    if ($model->existsFile($model->filename)) {
                        $model->deleteFile($model->filename);
                    }

                    if (is_null($model->main_category)) {
                        $action->deleteFromUrlCache($model);
                        $action->delete(Category::class, $model);
                    } else {
                        $modelsToUpdate = Category::where('main_category', $model->main_category)->where('position', '>', $model->position)->get();
                        if (method_exists(get_class($model), 'seoFields')) {
                            $model->seoFields()->delete();
                        }

                        $action->deleteFromUrlCache($model);
                        $model->delete();
                        foreach ($modelsToUpdate as $currentModel) {
                            $currentModel->update(['position' => $currentModel->position - 1]);
                        }
                    }
                }

                Category::cacheUpdate();

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $productCategory = Category::find($id);
            MainHelper::goBackIfNull($productCategory);

            if ($productCategory->subCategories->isNotEmpty()) {
                return back()->withErrors(['product::admin.product_categories.error_cant_delete_has_sub_categories']);
            }
            if ($productCategory->products->isNotEmpty()) {
                return back()->withErrors(['product::admin.product_categories.error_cant_delete_has_products']);
            }

            if (is_null($productCategory->main_category)) {
                $action->deleteFromUrlCache($productCategory);
                $action->delete(Category::class, $productCategory);
            } else {
                $modelsToUpdate = Category::where('main_category', $productCategory->main_category)->where('position', '>', $productCategory->position)->get();
                if (method_exists(get_class($productCategory), 'seoFields')) {
                    $productCategory->seoFields()->delete();
                }

                $action->deleteFromUrlCache($productCategory);
                $productCategory->delete();
                foreach ($modelsToUpdate as $currentModel) {
                    $currentModel->update(['position' => $currentModel->position - 1]);
                }

                Category::cacheUpdate();
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        public function update($id, ProductCategoryUpdateRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $productCategory = Category::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCategory);

            $action->validateImage($request, 'Product', 2);
            $request['main_category'] = $productCategory->main_category;
            $action->doSimpleUpdate(Category::class, CategoryTranslation::class, $productCategory, $request);
            $action->updateUrlCache($productCategory, CategoryTranslation::class);
            $action->updateSeo($request, $productCategory, 'Category');

            if ($request->has('image')) {
                $request->validate(['image' => Category::getFileRules()], [Category::getUserInfoMessage()]);
                $productCategory->saveFile($request->image);
            }

            Category::cacheUpdate();

            if (!is_null($productCategory->main_category)) {
                return redirect()->route('admin.product-categories.sub-categories.index', ['id' => $productCategory->main_category])->with('success-message', trans('admin.common.successful_create'));
            }

            return redirect()->route('admin.product-categories.index')->with('success-message', 'admin.common.successful_edit');
        }
        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(Category::class, $request, $active);
            Category::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function active($id, $active): RedirectResponse
        {
            $productCategory = Category::find($id);
            MainHelper::goBackIfNull($productCategory);

            $productCategory->update(['active' => $active]);
            Category::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionUp($id, CommonControllerAction $action): RedirectResponse
        {
            $productCategory = Category::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCategory);

            if (is_null($productCategory->main_category)) {
                $action->positionUp(Category::class, $productCategory);
            } else {
                $previousModel = Category::where('main_category', $productCategory->main_category)->where('position', $productCategory->position - 1)->first();
                if (!is_null($previousModel)) {
                    $previousModel->update(['position' => $previousModel->position + 1]);
                    $productCategory->update(['position' => $productCategory->position - 1]);
                }
            }

            Category::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }

        public function positionDown($id, CommonControllerAction $action): RedirectResponse
        {
            $productCategory = Category::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($productCategory);

            if (is_null($productCategory->main_category)) {
                $action->positionDown(Category::class, $productCategory);
            } else {
                $nextModel = Category::where('main_category', $productCategory->main_category)->where('position', $productCategory->position + 1)->first();
                if (!is_null($nextModel)) {
                    $nextModel->update(['position' => $nextModel->position - 1]);
                    $productCategory->update(['position' => $productCategory->position + 1]);
                }
            }

            Category::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function deleteImage($id, CommonControllerAction $action): RedirectResponse
        {
            $productCategory = Category::find($id);
            MainHelper::goBackIfNull($productCategory);

            if ($action->imageDelete($productCategory, Category::class)) {
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

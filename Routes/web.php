<?php

    use Illuminate\Support\Facades\Route;
    use Modules\Product\Http\Controllers\admin\Brands\BrandController;
    use Modules\Product\Http\Controllers\admin\ProductAttributes\ProductAttributesController;
    use Modules\Product\Http\Controllers\admin\ProductAttributes\ProductAttributeValuesController;
    use Modules\Product\Http\Controllers\admin\ProductCategories\ProductCategoriesController;
    use Modules\Product\Http\Controllers\admin\ProductCombinations\ProductCombinationsController;
    use Modules\Product\Http\Controllers\admin\Products\ProductCharacteristicsController;
    use Modules\Product\Http\Controllers\admin\Products\ProductsController;
    use Modules\Product\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUsersController;
    use Modules\Product\Http\Controllers\admin\Settings\Main\ShopMainSettingsController;
    use Modules\Product\Http\Controllers\admin\Settings\MeasuringUnits\MeasuringUnitsController;
    use Modules\Product\Http\Controllers\admin\Settings\ShopSettingsController;
    use Modules\Product\Http\Controllers\Front\FrontProductModuleController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    /*
     * FRONT ROUTES
     */

    Route::group(['prefix' => '{languageSlug}', 'where' => ['languageSlug' => '[a-zA-Z]{2}']], static function () {
        Route::post('inquiry-from-product/{productId}', [FrontProductModuleController::class, 'makeInquiry'])->name('front.contacts.make-product-inquiry');
    });

    /*
     * ADMIN ROUTES
     */
    Route::group(['prefix' => 'admin/products', 'middleware' => ['auth']], static function () {
        /* Settings */
        Route::group(['prefix' => 'settings'], static function () {
            Route::get('/', [ShopSettingsController::class, 'index'])->name('admin.product.settings.index');

            /* Main Settings */
            Route::group(['prefix' => 'main-settings'], static function () {
                Route::get('/', [ShopMainSettingsController::class, 'index'])->name('admin.product.settings.main.index');
                Route::post('update', [ShopMainSettingsController::class, 'update'])->name('admin.product.settings.main.update');
            });

            /* Measuring Units */
            Route::group(['prefix' => 'measuring-units'], static function () {
                Route::get('/', [MeasuringUnitsController::class, 'index'])->name('admin.measuring-units.index');
                Route::get('create', [MeasuringUnitsController::class, 'create'])->name('admin.measuring-units.create');
                Route::post('store', [MeasuringUnitsController::class, 'store'])->name('admin.measuring-units.store');

                Route::group(['prefix' => 'multiple'], static function () {
                    Route::get('delete', [MeasuringUnitsController::class, 'deleteMultiple'])->name('admin.measuring-units.delete-multiple');
                });

                Route::group(['prefix' => '{id}'], static function () {
                    Route::get('edit', [MeasuringUnitsController::class, 'edit'])->name('admin.measuring-units.edit');
                    Route::post('update', [MeasuringUnitsController::class, 'update'])->name('admin.measuring-units.update');
                    Route::get('delete', [MeasuringUnitsController::class, 'delete'])->name('admin.measuring-units.delete');
                });
            });
        });

        /* Brands */
        Route::group(['prefix' => 'brands'], static function () {
            Route::get('/', [BrandController::class, 'index'])->name('admin.brands.index');
            Route::get('/create', [BrandController::class, 'create'])->name('admin.brands.create');
            Route::post('/store', [BrandController::class, 'store'])->name('admin.brands.store');

            Route::group(['prefix' => 'multiple'], static function () {
                Route::get('active/{active}', [BrandController::class, 'activeMultiple'])->name('admin.brands.active-multiple');
                Route::get('delete', [BrandController::class, 'deleteMultiple'])->name('admin.brands.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
                Route::post('update', [BrandController::class, 'update'])->name('admin.brands.update');
                Route::get('delete', [BrandController::class, 'delete'])->name('admin.brands.delete');
                Route::get('show', [BrandController::class, 'show'])->name('admin.brands.show');
                Route::get('active/{active}', [BrandController::class, 'active'])->name('admin.brands.changeStatus');
                Route::get('position/up', [BrandController::class, 'positionUp'])->name('admin.brands.position-up');
                Route::get('position/down', [BrandController::class, 'positionDown'])->name('admin.brands.position-down');
                Route::get('image/delete', [BrandController::class, 'deleteImage'])->name('admin.brands.delete-image');
                Route::get('image/delete-logo', [BrandController::class, 'deleteLogo'])->name('admin.brands.delete-logo');
            });
        });

        /* Product Categories */
        Route::group(['prefix' => 'product-categories'], static function () {
            Route::get('/', [ProductCategoriesController::class, 'index'])->name('admin.product-categories.index');
            Route::get('/create', [ProductCategoriesController::class, 'create'])->name('admin.product-categories.create');
            Route::post('/store', [ProductCategoriesController::class, 'store'])->name('admin.product-categories.store');

            Route::group(['prefix' => 'multiple'], static function () {
                Route::get('active/{active}', [ProductCategoriesController::class, 'activeMultiple'])->name('admin.product-categories.active-multiple');
                Route::get('delete', [ProductCategoriesController::class, 'deleteMultiple'])->name('admin.product-categories.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [ProductCategoriesController::class, 'edit'])->name('admin.product-categories.edit');
                Route::post('update', [ProductCategoriesController::class, 'update'])->name('admin.product-categories.update');
                Route::get('delete', [ProductCategoriesController::class, 'delete'])->name('admin.product-categories.delete');
                Route::get('show', [ProductCategoriesController::class, 'show'])->name('admin.product-categories.show');
                Route::get('active/{active}', [ProductCategoriesController::class, 'active'])->name('admin.product-categories.changeStatus');
                Route::get('position/up', [ProductCategoriesController::class, 'positionUp'])->name('admin.product-categories.position-up');
                Route::get('position/down', [ProductCategoriesController::class, 'positionDown'])->name('admin.product-categories.position-down');
                Route::get('image/delete', [ProductCategoriesController::class, 'deleteImage'])->name('admin.product-categories.delete-image');
                Route::get('/products', [ProductCategoriesController::class, 'getCategoryProducts'])->name('admin.product-categories.products');

                /* Subcategories */
                Route::group(['prefix' => 'sub-categories'], static function () {
                    Route::get('/', [ProductCategoriesController::class, 'subCategoriesIndex'])->name('admin.product-categories.sub-categories.index');
                    Route::get('/create', [ProductCategoriesController::class, 'subCategoriesCreate'])->name('admin.product-categories.sub-categories.create');
                });
            });
        });

        /* Products */
        Route::group(['prefix' => 'products'], static function () {
            Route::get('/', [ProductsController::class, 'index'])->name('admin.products.index');

            /* Load products by category */
            Route::group(['prefix' => 'category/{category_id}'], static function () {
                Route::get('/', [ProductsController::class, 'getCategoryProducts'])->name('admin.products.index_by_category');
                Route::get('/create', [ProductsController::class, 'create'])->name('admin.products.create');
                Route::post('/store', [ProductsController::class, 'store'])->name('admin.products.store');
            });

            Route::group(['prefix' => 'multiple'], static function () {
                Route::get('active/{active}', [ProductsController::class, 'activeMultiple'])->name('admin.products.active-multiple');
                Route::get('delete', [ProductsController::class, 'deleteMultiple'])->name('admin.products.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
                Route::post('update', [ProductsController::class, 'update'])->name('admin.products.update');
                Route::get('delete', [ProductsController::class, 'delete'])->name('admin.products.delete');
                Route::get('show', [ProductsController::class, 'show'])->name('admin.products.show');
                Route::get('active/{active}', [ProductsController::class, 'active'])->name('admin.products.changeStatus');
                Route::get('position/up', [ProductsController::class, 'positionUp'])->name('admin.products.position-up');
                Route::get('position/down', [ProductsController::class, 'positionDown'])->name('admin.products.position-down');
                Route::get('image/delete', [ProductsController::class, 'deleteImage'])->name('admin.products.delete-image');
                Route::get('send-to-product-adboxes', [ProductsController::class, 'makeProductAdBox'])->name('admin.products.send-to-product-adboxes');
                Route::get('send-to-adboxes', [ProductsController::class, 'makeAdBox'])->name('admin.products.send-to-adboxes');
                Route::get('combinations', [ProductCombinationsController::class, 'combinationsByProductId'])->name('admin.products.combinations-by-product');
                /* Product characteristics for one product */
                Route::group(['prefix' => 'characteristics'], static function () {
                    Route::get('/', [ProductCharacteristicsController::class, 'characteristicsByProductId'])->name('admin.product_characteristics-by-product');
                    Route::post('/', [ProductCharacteristicsController::class, 'characteristicsByProductIdUpdate'])->name('admin.product_characteristics-by-product.update');
                });
            });
        });

        /* Product attributes */
        Route::group(['prefix' => 'product-attributes'], static function () {
            Route::get('/', [ProductAttributesController::class, 'index'])->name('admin.product-attributes.index');
            Route::get('/create', [ProductAttributesController::class, 'create'])->name('admin.product-attributes.create');
            Route::post('/store', [ProductAttributesController::class, 'store'])->name('admin.product-attributes.store');

            Route::group(['prefix' => 'multiple'], static function () {
                Route::get('active/{active}', [ProductAttributesController::class, 'activeMultiple'])->name('admin.product-attributes.active-multiple');
                Route::get('delete', [ProductAttributesController::class, 'deleteMultiple'])->name('admin.product-attributes.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [ProductAttributesController::class, 'edit'])->name('admin.product-attributes.edit');
                Route::post('update', [ProductAttributesController::class, 'update'])->name('admin.product-attributes.update');
                Route::get('delete', [ProductAttributesController::class, 'delete'])->name('admin.product-attributes.delete');
                Route::get('show', [ProductAttributesController::class, 'show'])->name('admin.product-attributes.show');
                Route::get('/active/{active}', [ProductAttributesController::class, 'active'])->name('admin.product-attributes.changeStatus');
                Route::get('position/up', [ProductAttributesController::class, 'positionUp'])->name('admin.product-attributes.position-up');
                Route::get('position/down', [ProductAttributesController::class, 'positionDown'])->name('admin.product-attributes.position-down');

                /* Product attribute values */
                Route::group(['prefix' => 'values'], static function () {
                    Route::get('/', [ProductAttributeValuesController::class, 'index'])->name('admin.product-attribute.values.index');
                    Route::get('create', [ProductAttributeValuesController::class, 'create'])->name('admin.product-attribute.values.create');
                    Route::post('store', [ProductAttributeValuesController::class, 'store'])->name('admin.product-attribute.values.store');

                    Route::group(['prefix' => 'multiple'], static function () {
                        Route::get('delete', [ProductAttributeValuesController::class, 'deleteMultiple'])->name('admin.product-attribute.values.delete-multiple');
                    });

                    Route::group(['prefix' => '{value_id}'], static function () {
                        Route::get('edit', [ProductAttributeValuesController::class, 'edit'])->name('admin.product-attribute.values.edit');
                        Route::post('update', [ProductAttributeValuesController::class, 'update'])->name('admin.product-attribute.values.update');
                        Route::get('delete', [ProductAttributeValuesController::class, 'delete'])->name('admin.product-attribute.values.delete');
                        Route::get('position/up', [ProductAttributeValuesController::class, 'positionUp'])->name('admin.product-attribute.values.position-up');
                        Route::get('position/down', [ProductAttributeValuesController::class, 'positionDown'])->name('admin.product-attribute.values.position-down');
                        Route::get('image/delete', [ProductAttributeValuesController::class, 'deleteImage'])->name('admin.product-attribute.values.delete-image');
                    });
                });
            });
        });
        /* Product characteristics */
        Route::group(['prefix' => 'product_characteristics'], static function () {
            Route::get('/', [ProductCharacteristicsController::class, 'index'])->name('admin.product_characteristics.index');
            Route::get('/create', [ProductCharacteristicsController::class, 'create'])->name('admin.product_characteristics.create');
            Route::post('/store', [ProductCharacteristicsController::class, 'store'])->name('admin.product_characteristics.store');

            Route::group(['prefix' => 'multiple'], static function () {
                Route::get('active/{active}', [ProductCharacteristicsController::class, 'activeMultiple'])->name('admin.product_characteristics.active-multiple');
                Route::get('delete', [ProductCharacteristicsController::class, 'deleteMultiple'])->name('admin.product_characteristics.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [ProductCharacteristicsController::class, 'edit'])->name('admin.product_characteristics.edit');
                Route::post('update', [ProductCharacteristicsController::class, 'update'])->name('admin.product_characteristics.update');
                Route::get('delete', [ProductCharacteristicsController::class, 'delete'])->name('admin.product_characteristics.delete');
                Route::get('show', [ProductCharacteristicsController::class, 'show'])->name('admin.product_characteristics.show');
                Route::get('/active/{active}', [ProductCharacteristicsController::class, 'active'])->name('admin.product_characteristics.changeStatus');
                Route::get('/active/single/{active}', [ProductCharacteristicsController::class, 'active'])->name('admin.product_characteristics.single-changeStatus');
                Route::get('position/up', [ProductCharacteristicsController::class, 'positionUp'])->name('admin.product_characteristics.position-up');
                Route::get('position/down', [ProductCharacteristicsController::class, 'positionDown'])->name('admin.product_characteristics.position-down');
            });
        });
        /* Product combinations */
        Route::group(['prefix' => 'product_combinations'], static function () {
            Route::get('/', [ProductCombinationsController::class, 'index'])->name('admin.product-combinations.index');
            Route::post('generate', [ProductCombinationsController::class, 'generate'])->name('admin.product-combinations.generate');
            Route::post('/getAttributesByProductCategory', [ProductCombinationsController::class, 'getAttributesByProductCategory'])->name('admin.product-combinations.getAttributesByProductCategory');
            Route::post('/getProductSkuNumber', [ProductCombinationsController::class, 'getProductSkuNumber'])->name('admin.product-combinations.getProductSkuNumber');
            Route::post('/generateSkuNumbersByProducts', [ProductCombinationsController::class, 'generateSkuNumbersByProducts'])->name('admin.product-combinations.generate-sku-numbers-by-products');
            Route::post('/generateSkuNumbersByProduct', [ProductCombinationsController::class, 'generateSkuNumbersByProduct'])->name('admin.product-combinations.generate-sku-numbers-by-product');

            Route::group(['prefix' => 'multiple'], static function () {
                Route::post('update', [ProductCombinationsController::class, 'updateMultiple'])->name('admin.product-combinations.update-multiple');
                Route::get('delete', [ProductCombinationsController::class, 'deleteMultiple'])->name('admin.product-combinations.delete-multiple');
            });

            Route::group(['prefix' => '{id}'], static function () {
                Route::post('update', [ProductCombinationsController::class, 'update'])->name('admin.product-combinations.update');
                Route::get('delete', [ProductCombinationsController::class, 'delete'])->name('admin.product-combinations.delete');
            });
        });
    });

<?php

use App\Http\Controllers\Shop\Frontend\Profile\FirmController;
use App\Http\Controllers\Shop\Frontend\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\admin\AdBoxesProducts\AdBoxesProductsController;
use Modules\Shop\Http\Controllers\admin\Brands\BrandController;
use Modules\Shop\Http\Controllers\admin\Orders\Documents\OrderDocumentController;
use Modules\Shop\Http\Controllers\admin\Orders\OrderReturnsController;
use Modules\Shop\Http\Controllers\admin\Orders\OrdersController;
use Modules\Shop\Http\Controllers\admin\ProductAttributes\ProductAttributesController;
use Modules\Shop\Http\Controllers\admin\ProductAttributes\ProductAttributeValuesController;
use Modules\Shop\Http\Controllers\admin\ProductCategories\ProductCategoriesController;
use Modules\Shop\Http\Controllers\admin\ProductCollections\CollectionsController;
use Modules\Shop\Http\Controllers\admin\ProductCombinations\ProductCombinationsController;
use Modules\Shop\Http\Controllers\admin\Products\ProductCharacteristicsController;
use Modules\Shop\Http\Controllers\admin\Products\ProductsController;
use Modules\Shop\Http\Controllers\admin\ProductStocks\InternalSupplierController;
use Modules\Shop\Http\Controllers\admin\ProductStocks\ProductStocksController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUserCompaniesController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUserFavProductsController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUserOrdersController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUserPaymentAddressController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUsersController;
use Modules\Shop\Http\Controllers\admin\RegisteredUsers\ShopAdminRegisteredUserShipmentAddressController;
use Modules\Shop\Http\Controllers\admin\Settings\Currencies\CurrenciesController;
use Modules\Shop\Http\Controllers\admin\Settings\Deliveries\DeliveriesController;
use Modules\Shop\Http\Controllers\admin\Settings\InternalIntegrations\InternalIntegrationsController;
use Modules\Shop\Http\Controllers\admin\Settings\Main\ShopMainSettingsController;
use Modules\Shop\Http\Controllers\admin\Settings\MeasuringUnits\MeasuringUnitsController;
use Modules\Shop\Http\Controllers\admin\Settings\Payments\PaymentsController;
use Modules\Shop\Http\Controllers\admin\Settings\ShopSettingsController;
use Modules\Shop\Http\Controllers\admin\Settings\Vats\VatsController;
use Modules\Shop\Http\Controllers\admin\Settings\ZipCodes\CityZipCodesController;
use Modules\Shop\Http\Controllers\admin\ShopAdminHomeController;
use Modules\Shop\Http\Controllers\Auth\ShopForgotPasswordController;
use Modules\Shop\Http\Controllers\Auth\ShopLoginController;
use Modules\Shop\Http\Controllers\Auth\ShopRegisterController;
use Modules\Shop\Http\Controllers\Auth\ShopResetPasswordController;
use Modules\Shop\Http\Controllers\Auth\ShopVerificationController;
use Modules\Shop\Http\Controllers\BasketController;
use Modules\Shop\Http\Controllers\CartController;
use Modules\Shop\Http\Controllers\Front\RegisteredUser\CompaniesController;
use Modules\Shop\Http\Controllers\Front\RegisteredUser\RegisteredUserAccountController;
use Modules\Shop\Http\Controllers\HomeController;

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
 * ADMIN ROUTES
 */
Route::group(['prefix' => 'admin/shop', 'middleware' => ['auth']], static function () {

    /* Common */
    Route::post('/getClientGroupById', [ShopAdminRegisteredUsersController::class, 'getClientGroupById']);
    /* Dashboard */
    Route::get('/', [ShopAdminHomeController::class, 'index'])->name('admin.shop.dashboard');

    /* Orders */
    Route::group(['prefix' => 'orders'], static function () {
        Route::get('/', [OrdersController::class, 'index'])->name('admin.shop.orders');
        Route::get('/create', [OrdersController::class, 'create'])->name('admin.shop.orders.create');
        Route::post('/store', [OrdersController::class, 'store'])->name('admin.shop.orders.store');

        Route::post('/changeOrderStatus', [OrdersController::class, 'changeOrderStatus'])->name('admin.shop.orders.change-status');
        Route::post('/getProductByIdForOrder', [OrdersController::class, 'getProductByIdForOrder']);
        Route::post('/changePaymentType', [OrdersController::class, 'changePaymentType']);
        Route::post('/changeDeliveryType', [OrdersController::class, 'changeDeliveryType']);
        Route::post('/changeFirmInfo', [OrdersController::class, 'changeFirmInfo']);
        Route::post('/changeProductQuantity', [OrdersController::class, 'changeProductQuantity']);
        Route::post('/deleteProduct', [OrdersController::class, 'deleteProduct']);

        /* ID */
        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [OrdersController::class, 'edit'])->name('admin.shop.orders.edit');
            Route::post('update', [OrdersController::class, 'update'])->name('admin.shop.orders.update');
            Route::get('delete', [OrdersController::class, 'revoke'])->name('admin.shop.orders.revoke');
            Route::get('show', [OrdersController::class, 'show'])->name('admin.shop.orders.show');
            Route::post('return/update', [OrdersController::class, 'returnUpdate'])->name('admin.shop.orders.return-update');
            Route::post('payment/update', [OrdersController::class, 'paymentUpdate'])->name('admin.shop.orders.payment-update');
            Route::post('virtual-receipt/generate', [OrdersController::class, 'virtualReceiptGenerate'])->name('admin.shop.orders.virtual-receipt-generate');
            /* Update Status */
            Route::post('payment-status/update', [OrdersController::class, 'paymentStatusUpdate'])->name('admin.shop.orders.payment-status-update');
            Route::post('shipment-status/update', [OrdersController::class, 'shipmentStatusUpdate'])->name('admin.shop.orders.shipment-status-update');
        });

        /* Documents */
        Route::group(['prefix' => '{order_id}/documents'], static function () {
            Route::get('/', [OrderDocumentController::class, 'index'])->name('admin.shop.orders.documents.index');
            Route::get('/create', [OrderDocumentController::class, 'create'])->name('admin.shop.orders.documents.create');
            Route::post('/store', [OrderDocumentController::class, 'store'])->name('admin.shop.orders.documents.store');
            Route::group(['prefix' => '{document_id}'], static function () {
                Route::get('delete', [OrderDocumentController::class, 'delete'])->name('admin.shop.orders.documents.delete');
                Route::get('show', [OrderDocumentController::class, 'show'])->name('admin.shop.orders.documents.show');
                Route::get('send', [OrderDocumentController::class, 'send'])->name('admin.shop.orders.documents.send');
            });
        });

        /* Returns */
        Route::group(['prefix' => 'returns'], static function () {
            Route::get('/', [OrderReturnsController::class, 'index'])->name('orders.returns.index');
            Route::get('/{id}/show', [OrderReturnsController::class, 'show'])->name('orders.returns.show');
            Route::post('/changeOrderReturnStatus', [OrderReturnsController::class, 'changeOrderReturnStatus']);
        });
    });

    /* Clients - registered users */
    Route::group(['prefix' => 'registered-users'], static function () {
        Route::get('/', [ShopAdminRegisteredUsersController::class, 'index'])->name('admin.shop.registered-users.index');
        Route::get('/create', [ShopAdminRegisteredUsersController::class, 'create'])->name('admin.shop.registered-users.create');
        Route::post('/store', [ShopAdminRegisteredUsersController::class, 'store'])->name('admin.shop.registered-users.store');
        Route::post('getAjaxClientById', [ShopAdminRegisteredUsersController::class, 'getAjaxClientById'])->name('admin.shop.registered-users.get-ajax-user-by-id');

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [ShopAdminRegisteredUsersController::class, 'edit'])->name('admin.shop.registered-users.edit');
            Route::post('update', [ShopAdminRegisteredUsersController::class, 'update'])->name('admin.shop.registered-users.update');
            Route::get('show', [ShopAdminRegisteredUsersController::class, 'show'])->name('admin.shop.registered-users.show');
            Route::get('active/{active}', [ShopAdminRegisteredUsersController::class, 'changeActiveStatus'])->name('admin.shop.registered-users.changeStatus');

            /* Orders */
            //            TODO: Ne e napisano
            Route::group(['prefix' => 'orders'], static function () {
                Route::get('/', [ShopAdminRegisteredUserOrdersController::class, 'index'])->name('admin.shop.registered-users.orders.index');
                Route::get('/{order_id}/show', [ShopAdminRegisteredUserOrdersController::class, 'show'])->name('admin.shop.registered-users.orders.show');
                Route::get('/{order_id}/edit', [ShopAdminRegisteredUserOrdersController::class, 'edit'])->name('admin.shop.registered-users.orders.edit');
                Route::post('/{order_id}/update', [ShopAdminRegisteredUserOrdersController::class, 'update'])->name('admin.shop.registered-users.orders.update');
            });

            /* Returned products */
            //            TODO: Ne e napisano
            Route::group(['prefix' => 'returned-products'], static function () {
                Route::get('/', [ShopAdminRegisteredUsersController::class, 'index'])->name('admin.shop.registered-users.returned-products.index');
                Route::get('/{returned_product_id}/edit', [ShopAdminRegisteredUsersController::class, 'edit'])->name('admin.shop.registered-users.returned-products.edit');
                Route::post('/{returned_product_id}/update', [ShopAdminRegisteredUsersController::class, 'update'])->name('admin.shop.registered-users.returned-products.update');
                Route::get('/{returned_product_id}/show', [ShopAdminRegisteredUsersController::class, 'show'])->name('admin.shop.registered-users.returned-products.show');
            });

            /* Abandoned baskets */
            //            TODO: Ne e napisano
            Route::group(['prefix' => 'abandoned-baskets'], static function () {
                Route::get('/', [ShopAdminRegisteredUsersController::class, 'index'])->name('admin.shop.registered-users.abandoned-baskets.index');
            });

            /* Favorite products */
            Route::group(['prefix' => 'favorite-products'], static function () {
                Route::get('/', [ShopAdminRegisteredUserFavProductsController::class, 'index'])->name('admin.shop.registered-users.favorite-products.index');
            });

            /* Companies */
            Route::group(['prefix' => 'companies'], static function () {
                Route::get('/', [ShopAdminRegisteredUserCompaniesController::class, 'index'])->name('admin.shop.registered-users.companies.index');
                Route::get('/create', [ShopAdminRegisteredUserCompaniesController::class, 'create'])->name('admin.shop.registered-users.companies.create');
                Route::post('/store', [ShopAdminRegisteredUserCompaniesController::class, 'store'])->name('admin.shop.registered-users.companies.store');

                Route::group(['prefix' => '{company_id}'], static function () {
                    Route::get('edit', [ShopAdminRegisteredUserCompaniesController::class, 'edit'])->name('admin.shop.registered-users.companies.edit');
                    Route::post('update', [ShopAdminRegisteredUserCompaniesController::class, 'update'])->name('admin.shop.registered-users.companies.update');
                    Route::get('delete', [ShopAdminRegisteredUserCompaniesController::class, 'delete'])->name('admin.shop.registered-users.companies.delete');
                    Route::get('make-default', [ShopAdminRegisteredUserCompaniesController::class, 'setAsDefault'])->name('admin.shop.registered-users.companies.make-default');
                });
            });

            /* Shipment addresses */
            Route::group(['prefix' => 'shipment-addresses'], static function () {
                Route::get('/', [ShopAdminRegisteredUserShipmentAddressController::class, 'index'])->name('admin.shop.registered-users.shipment-addresses.index');
                Route::get('create', [ShopAdminRegisteredUserShipmentAddressController::class, 'create'])->name('admin.shop.registered-users.shipment-addresses.create');
                Route::post('store', [ShopAdminRegisteredUserShipmentAddressController::class, 'store'])->name('admin.shop.registered-users.shipment-addresses.store');
                Route::get('getStates', [ShopAdminRegisteredUserShipmentAddressController::class, 'getStates'])->name('admin.shop.registered-users.shipment-addresses.get-states');
                Route::get('getCities', [ShopAdminRegisteredUserShipmentAddressController::class, 'getCities'])->name('admin.shop.registered-users.shipment-addresses.get-cities');

                Route::group(['prefix' => '{address_id}'], static function () {
                    Route::get('edit', [ShopAdminRegisteredUserShipmentAddressController::class, 'edit'])->name('admin.shop.registered-users.shipment-addresses.edit');
                    Route::post('update', [ShopAdminRegisteredUserShipmentAddressController::class, 'update'])->name('admin.shop.registered-users.shipment-addresses.update');
                    Route::get('delete', [ShopAdminRegisteredUserShipmentAddressController::class, 'delete'])->name('admin.shop.registered-users.shipment-addresses.delete');
                    Route::get('make-default', [ShopAdminRegisteredUserShipmentAddressController::class, 'setAsDefault'])->name('admin.shop.registered-users.shipment-addresses.make-default');
                });
            });

            /* Payment addresses */
            Route::group(['prefix' => 'payment-addresses'], static function () {
                Route::get('/', [ShopAdminRegisteredUserPaymentAddressController::class, 'index'])->name('admin.shop.registered-users.payment-addresses.index');
                Route::get('create', [ShopAdminRegisteredUserPaymentAddressController::class, 'create'])->name('admin.shop.registered-users.payment-addresses.create');
                Route::post('store', [ShopAdminRegisteredUserPaymentAddressController::class, 'store'])->name('admin.shop.registered-users.payment-addresses.store');
                Route::get('getStates', [ShopAdminRegisteredUserPaymentAddressController::class, 'getStates'])->name('admin.shop.registered-users.payment-addresses.get-states');
                Route::get('getCities', [ShopAdminRegisteredUserPaymentAddressController::class, 'getCities'])->name('admin.shop.registered-users.payment-addresses.get-cities');

                Route::group(['prefix' => '{address_id}'], static function () {
                    Route::get('edit', [ShopAdminRegisteredUserPaymentAddressController::class, 'edit'])->name('admin.shop.registered-users.payment-addresses.edit');
                    Route::post('update', [ShopAdminRegisteredUserPaymentAddressController::class, 'update'])->name('admin.shop.registered-users.payment-addresses.update');
                    Route::get('delete', [ShopAdminRegisteredUserPaymentAddressController::class, 'delete'])->name('admin.shop.registered-users.payment-addresses.delete');
                    Route::get('make-default', [ShopAdminRegisteredUserPaymentAddressController::class, 'setAsDefault'])->name('admin.shop.registered-users.payment-addresses.make-default');
                });
            });
        });
    });

    /* Settings */
    Route::group(['prefix' => 'settings'], static function () {
        Route::get('/', [ShopSettingsController::class, 'index'])->name('admin.shop.settings.index');

        /* Main Settings */
        Route::group(['prefix' => 'main-settings'], static function () {
            Route::get('/', [ShopMainSettingsController::class, 'index'])->name('admin.shop.settings.main.index');
            Route::post('update', [ShopMainSettingsController::class, 'update'])->name('admin.shop.settings.main.update');
        });

        /* Payment settings */
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
            Route::get('/edit/{id}', [PaymentsController::class, 'edit'])->name('payments.edit');
            Route::post('/update/{id}', [PaymentsController::class, 'update'])->name('payments.update');
            Route::get('/update/state/{id}/{active}', [PaymentsController::class, 'updateState'])->name('payments.update_state');
            Route::get('/update/position/{id}/{position}', [PaymentsController::class, 'updatePosition'])->name('payments.update_option');
        });

        /* Delivery settings */
        Route::prefix('deliveries')->group(function () {
            Route::get('/', [DeliveriesController::class, 'index'])->name('deliveries.index');
            Route::get('/edit/{id}', [DeliveriesController::class, 'edit'])->name('deliveries.edit');
            Route::post('/update/{id}', [DeliveriesController::class, 'update'])->name('deliveries.update');
            Route::get('/update/state/{id}/{active}', [DeliveriesController::class, 'updateState'])->name('deliveries.update_state');
            Route::get('/update/position/{id}/{position}', [DeliveriesController::class, 'updatePosition'])->name('deliveries.update_option');
        });

        /* Zip codes settings */
        Route::prefix('zipcodes')->group(function () {
            Route::get('/', [CityZipCodesController::class, 'index'])->name('zip_codes.index');
            Route::get('/edit/{id}', [CityZipCodesController::class, 'edit'])->name('zip_codes.edit');
            Route::post('/update/{id}', [CityZipCodesController::class, 'update'])->name('zip_codes.update');
        });

        /* Vats settings default */
        Route::prefix('vats')->group(function () {
            Route::prefix('countries')->group(function () {
                Route::get('/', [VatsController::class, 'index'])->name('vats.countries.index');
                Route::get('/edit/{id}', [VatsController::class, 'edit'])->name('vats.countries.edit');
                Route::post('/update/{id}', [VatsController::class, 'update'])->name('vats.countries.update');

                Route::prefix('states')->group(function () {
                    Route::get('{id}/', [VatsController::class, 'states'])->name('vats.countries.states.index');
                    Route::get('/edit/{id}', [VatsController::class, 'statesEdit'])->name('vats.countries.states.edit');
                    Route::post('/update/{id}', [VatsController::class, 'statesUpdate'])->name('vats.countries.states.update');

                    Route::prefix('cities')->group(function () {
                        Route::get('{id}/', [VatsController::class, 'cities'])->name('vats.countries.states.cities.index');
                        Route::get('/edit/{id}', [VatsController::class, 'citiesEdit'])->name('vats.countries.states.cities.edit');
                        Route::post('/update/{id}', [VatsController::class, 'citiesUpdate'])->name('vats.countries.states.cities.update');
                    });
                });
            });

            /* Vat categories */
            Route::prefix('categories')->group(function () {
                Route::prefix('countries')->group(function () {
                    Route::get('{id}/', [VatsController::class, 'categories'])->name('vats.countries.categories.index');
                    Route::get('{id}/create', [VatsController::class, 'categoriesCreate'])->name('vats.countries.categories.create');
                    Route::post('{id}/store', [VatsController::class, 'categoriesStore'])->name('vats.countries.categories.store');
                    Route::get('/edit/{id}', [VatsController::class, 'categoriesEdit'])->name('vats.countries.categories.edit');
                    Route::post('/update/{id}', [VatsController::class, 'categoriesUpdate'])->name('vats.countries.categories.update');
                    Route::get('/delete/{id}', [VatsController::class, 'categoriesDestroy'])->name('vats.countries.categories.delete');

                    Route::prefix('states')->group(function () {
                        Route::get('{id}/', [VatsController::class, 'statesCategories'])->name('vats.countries.states.categories.index');
                        Route::get('{id}/create', [VatsController::class, 'statesCategoriesCreate'])->name('vats.countries.states.categories.create');
                        Route::post('{id}/store', [VatsController::class, 'statesCategoriesStore'])->name('vats.countries.states.categories.store');
                        Route::get('/edit/{id}', [VatsController::class, 'statesCategoriesEdit'])->name('vats.countries.states.categories.edit');
                        Route::post('/update/{id}', [VatsController::class, 'statesCategoriesUpdate'])->name('vats.countries.states.categories.update');
                        Route::get('/delete/{id}', [VatsController::class, 'statesCategoriesDestroy'])->name('vats.countries.states.categories.delete');

                        Route::prefix('cities')->group(function () {
                            Route::get('{id}/', [VatsController::class, 'citiesCategories'])->name('vats.countries.states.cities.categories.index');
                            Route::get('{id}/create', [VatsController::class, 'citiesCategoriesCreate'])->name('vats.countries.states.cities.categories.create');
                            Route::post('{id}/store', [VatsController::class, 'citiesCategoriesStore'])->name('vats.countries.states.cities.categories.store');
                            Route::get('/edit/{id}', [VatsController::class, 'citiesCategoriesEdit'])->name('vats.countries.states.cities.categories.edit');
                            Route::post('/update/{id}', [VatsController::class, 'citiesCategoriesUpdate'])->name('vats.countries.states.cities.categories.update');
                            Route::get('/delete/{id}', [VatsController::class, 'citiesCategoriesDestroy'])->name('vats.countries.states.cities.categories.delete');
                        });
                    });
                });
            });
        });

        /* Currencies */
        //TODO: Ne e praveno - zapochnato, da se saobrazi sprqmo izbranoto country za prodajba
        //TODO: Samo na tezi valuti da se vzimat currency_rates
        Route::prefix('currencies')->group(function () {
            Route::get('/', [CurrenciesController::class, 'index'])->name('admin.currencies.index');
            Route::get('create', [CurrenciesController::class, 'create'])->name('admin.currencies.create');

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [CurrenciesController::class, 'edit'])->name('admin.currencies.edit');
                Route::post('update', [CurrenciesController::class, 'update'])->name('admin.currencies.update');
                Route::get('manual-exchange-rate-update', [CurrenciesController::class, 'manualExchangeRateUpdate'])->name('admin.currencies.manual-exchange-rate-update');
            });
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

        /* Internal integrations */
        Route::group(['prefix' => 'internal-integrations'], static function () {
            Route::get('/', [InternalIntegrationsController::class, 'index'])->name('admin.shop.settings.internal-integrations.index');

            /* MailChimp */
            Route::group(['prefix' => 'mail-chimp'], static function () {
                Route::get('edit', [InternalIntegrationsController::class, 'mailChimpEdit'])->name('admin.shop.settings.internal-integrations.mail-chimp.edit');
                Route::post('update', [InternalIntegrationsController::class, 'mailChimpUpdate'])->name('admin.shop.settings.internal-integrations.mail-chimp.update');
            });

            /* ExchangeRate API */
            Route::group(['prefix' => 'exchange-rate'], static function () {
                Route::get('edit', [InternalIntegrationsController::class, 'exchangeRateEdit'])->name('admin.shop.settings.internal-integrations.exchange-rate.edit');
                Route::post('update', [InternalIntegrationsController::class, 'exchangeRateUpdate'])->name('admin.shop.settings.internal-integrations.exchange-rate.update');
            });
        });
    });

    Route::post('/user/location', [HomeController::class, 'setUserLocation'])->name('user.location.set');

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
                Route::get('/', [ProductCharacteristicsController::class, 'characteristicsByProductId'])->name('admin.products.characteristics-by-product');
                Route::post('/', [ProductCharacteristicsController::class, 'characteristicsByProductIdUpdate'])->name('admin.products.characteristics-by-product.update');
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
        Route::get('/', [ProductCharacteristicsController::class, 'index'])->name('admin.products.characteristics.index');
        Route::get('/create', [ProductCharacteristicsController::class, 'create'])->name('admin.products.characteristics.create');
        Route::post('/store', [ProductCharacteristicsController::class, 'store'])->name('admin.products.characteristics.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [ProductCharacteristicsController::class, 'activeMultiple'])->name('admin.products.characteristics.active-multiple');
            Route::get('delete', [ProductCharacteristicsController::class, 'deleteMultiple'])->name('admin.products.characteristics.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [ProductCharacteristicsController::class, 'edit'])->name('admin.products.characteristics.edit');
            Route::post('update', [ProductCharacteristicsController::class, 'update'])->name('admin.products.characteristics.update');
            Route::get('delete', [ProductCharacteristicsController::class, 'delete'])->name('admin.products.characteristics.delete');
            Route::get('show', [ProductCharacteristicsController::class, 'show'])->name('admin.products.characteristics.show');
            Route::get('/active/{active}', [ProductCharacteristicsController::class, 'active'])->name('admin.products.characteristics.changeStatus');
            Route::get('/active/single/{active}', [ProductCharacteristicsController::class, 'active'])->name('admin.products.characteristics.single-changeStatus');
            Route::get('position/up', [ProductCharacteristicsController::class, 'positionUp'])->name('admin.products.characteristics.position-up');
            Route::get('position/down', [ProductCharacteristicsController::class, 'positionDown'])->name('admin.products.characteristics.position-down');
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

    /* Product collections */
    Route::group(['prefix' => 'collections'], static function () {
        Route::get('/', [CollectionsController::class, 'index'])->name('admin.product-collections.index');
        Route::get('create', [CollectionsController::class, 'create'])->name('admin.product-collections.create');
        Route::post('store', [CollectionsController::class, 'store'])->name('admin.product-collections.store');

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [CollectionsController::class, 'edit'])->name('admin.product-collections.edit');
            Route::post('update', [CollectionsController::class, 'update'])->name('admin.product-collections.update');
            Route::get('delete', [CollectionsController::class, 'delete'])->name('admin.product-collections.delete');
            Route::get('/active/{active}', [CollectionsController::class, 'changeActiveStatus'])->name('admin.product-collections.changeActiveStatus');
            Route::get('show', [CollectionsController::class, 'show'])->name('admin.product-collections.show');
        });
    });
    /* Product stocks */
    //TODO: ne e praveno
    Route::group(['prefix' => 'product_stocks'], static function () {
        Route::get('/', [ProductStocksController::class, 'index'])->name('admin.product-stocks.index');
        Route::get('create', [ProductStocksController::class, 'create'])->name('admin.product-stocks.create');
        Route::post('store', [ProductStocksController::class, 'store'])->name('admin.product-stocks.store');

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [ProductStocksController::class, 'edit'])->name('admin.product-stocks.edit');
            Route::post('update', [ProductStocksController::class, 'update'])->name('admin.product-stocks.update');
            Route::get('show', [ProductStocksController::class, 'show'])->name('admin.product-stocks.show');
        });

        /* Internal suppliers */
        Route::group(['prefix' => 'internal_suppliers'], static function () {
            Route::get('/', [InternalSupplierController::class, 'index'])->name('admin.product-stocks.internal-suppliers.index');
            Route::get('archived', [InternalSupplierController::class, 'archived'])->name('admin.product-stocks.internal-suppliers.archived');
            Route::get('/create', [InternalSupplierController::class, 'create'])->name('admin.product-stocks.internal-suppliers.create');
            Route::post('/store', [InternalSupplierController::class, 'store'])->name('admin.product-stocks.internal-suppliers.store');

            Route::group(['prefix' => '{id}'], static function () {
                Route::get('edit', [InternalSupplierController::class, 'edit'])->name('admin.product-stocks.internal-suppliers.edit');
                Route::post('update', [InternalSupplierController::class, 'update'])->name('admin.product-stocks.internal-suppliers.update');
                Route::get('show', [InternalSupplierController::class, 'show'])->name('admin.product-stocks.internal-suppliers.show');
                Route::get('archive/{archived}', [InternalSupplierController::class, 'archive'])->name('admin.product-stocks.internal-suppliers.change-archive-status');
            });
        });
    });

    /* Product Adboxes */
    Route::group(['prefix' => 'product-adboxes'], static function () {
        Route::get('/', [AdBoxesProductsController::class, 'index'])->name('admin.product-adboxes.index');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [AdBoxesProductsController::class, 'activeMultiple'])->name('admin.product-adboxes.active-multiple');
            Route::get('delete', [AdBoxesProductsController::class, 'deleteMultiple'])->name('admin.product-adboxes.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [AdBoxesProductsController::class, 'edit'])->name('admin.product-adboxes.edit');
            Route::post('update', [AdBoxesProductsController::class, 'update'])->name('admin.product-adboxes.update');
            Route::get('delete', [AdBoxesProductsController::class, 'delete'])->name('admin.product-adboxes.delete');
            Route::get('active/{active}', [AdBoxesProductsController::class, 'active'])->name('admin.product-adboxes.changeStatus');
            Route::get('position/up', [AdBoxesProductsController::class, 'positionUp'])->name('admin.product-adboxes.position-up');
            Route::get('position/down', [AdBoxesProductsController::class, 'positionDown'])->name('admin.product-adboxes.position-down');
            Route::get('return_to_waiting', [AdBoxesProductsController::class, 'returnToWaiting'])->name('admin.product-adboxes.return-to-waiting');
        });
    });
});

/*
 * FRONT ROUTES
 */

Route::middleware(['web', 'set.sbuuid'])->group(function () {
    Route::prefix('basket')->group(function () {
        Route::get('/', [BasketController::class, 'index'])->name('basket.index');
        Route::post('add', [BasketController::class, 'addProduct'])->name('basket.products.add');

        /* Order */
        Route::group(['prefix' => 'order'], static function () {
            Route::get('preview/{id}', [BasketController::class, 'previewOrder'])->name('basket.order.preview');
            Route::get('create', [BasketController::class, 'createOrder'])->name('basket.order.create');
            Route::post('store', [BasketController::class, 'storeOrder'])->name('basket.order.store');
        });
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
    });

    /* Shop Auth */
    Route::group(['middleware' => ['web'], 'prefix' => '{languageSlug}/shop'], static function () {

        // Dashboard Registered user routes
        Route::group(['middleware' => ['auth:shop']], function () {
            Route::get('dashboard', [RegisteredUserAccountController::class, 'dashboard'])->name('shop.dashboard');
            /* Account */
            Route::group(['prefix' => 'account'], static function () {
                Route::get('personal-data', [RegisteredUserAccountController::class, 'personalData'])->name('shop.registered_user.account.personal-data');
                Route::post('/{id}/update', [RegisteredUserAccountController::class, 'update'])->name('shop.registered_user.account.personal-data.update');
                Route::post('/subscribe', [RegisteredUserAccountController::class, 'subscribe'])->name('shop.registered_user.account.subscribe');

                /* Companies */
                Route::group(['prefix' => 'companies'], static function () {
                    Route::get('/', [CompaniesController::class, 'index'])->name('shop.registered_user.account.companies');
                    Route::get('create', [CompaniesController::class, 'create'])->name('shop.registered_user.account.companies.create');
                    Route::post('store', [CompaniesController::class, 'store'])->name('shop.registered_user.account.companies.store');
                    Route::get('{id}/edit', [CompaniesController::class, 'edit'])->name('shop.registered_user.account.companies.edit');
                    Route::post('{id}/update', [CompaniesController::class, 'update'])->name('shop.registered_user.account.companies.update');
                    Route::get('{id}/delete', [CompaniesController::class, 'delete'])->name('shop.registered_user.account.companies.delete');
                });
            });
        });

        // Authentication routes
        Route::get('login', [ShopLoginController::class, 'showLoginForm'])->name('shop.login');
        Route::post('login', [ShopLoginController::class, 'login']);
        Route::post('logout', [ShopLoginController::class, 'logout'])->name('shop.logout');

        // Password reset routes
        Route::get('password/reset', [ShopForgotPasswordController::class, 'showLinkRequestForm'])->name('shop.password.request');
        Route::post('password/email', [ShopForgotPasswordController::class, 'sendResetLinkEmail'])->name('shop.password.email');
        Route::get('password/reset/{token}', [ShopResetPasswordController::class, 'showResetForm'])->name('shop.password.reset');
        Route::post('password/reset', [ShopResetPasswordController::class, 'reset'])->name('shop.password.update');

        // Registration routes
        Route::get('register', [ShopRegisterController::class, 'showRegistrationForm'])->name('shop.register');
        Route::post('register', [ShopRegisterController::class, 'register'])->name('shop.register.submit');

        // Email verification routes
        Route::group(['middleware' => ['auth:shop', 'verified']], function () {
            Route::get('verification/notice', [ShopVerificationController::class, 'show'])->name('shop.verification.notice');
            Route::get('verification/verify/{id}/{hash}', [ShopVerificationController::class, 'verify'])->name('shop.verification.verify');
            Route::get('verification/resend', [ShopVerificationController::class, 'resend'])->name('shop.verification.resend');
        });
    });
});



<?php

    namespace Modules\Product\Http\Controllers\admin\Settings\MeasuringUnits;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use App\Helpers\WebsiteHelper;
    use App\Http\Controllers\Controller;
    use App\Models\ProductCategory;
    use Cache;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Modules\Product\Entities\Settings\MeasureUnit;
    use Modules\Product\Entities\Settings\MeasureUnitTranslation;
    use Modules\Product\Http\Requests\ProductAttributeStoreRequest;
    use Modules\Product\Http\Requests\ProductAttributeUpdateRequest;

    class MeasuringUnitsController extends Controller
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN))) {
                MeasureUnit::cacheUpdate();
            }

            return view('product::admin.settings.measure_units.index', ['units' => Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN)]);
        }
        public function store(ProductAttributeStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $measureUnit = $action->doSimpleCreate(MeasureUnit::class, $request);

            MeasureUnit::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.measuring-units.index')->with('success-message', trans('admin.common.successful_create'));
        }
        public function edit($id)
        {
            $measureUnit = MeasureUnit::where('id', $id)->with('translations')->first();
            WebsiteHelper::redirectBackIfNull($measureUnit);

            if (is_null(Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN))) {
                MeasureUnit::cacheUpdate();
            }

            return view('product::admin.settings.measure_units.edit', [
                'unit'      => $measureUnit,
                'languages' => LanguageHelper::getActiveLanguages(),
            ]);
        }

        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $ids = array_map('intval', explode(',', $request->ids[0]));
                foreach ($ids as $id) {
                    $model = MeasureUnit::find($id);
                    if (is_null($model)) {
                        continue;
                    }

                    if ($model->products->isNotEmpty()) {
                        return back()->withErrors(['product::admin.measure_units.cant_delete_has_products_associated']);
                    }

                    $model->delete();
                }

                MeasureUnit::cacheUpdate();

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }

        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $measureUnit = MeasureUnit::where('id', $id)->with('products')->first();
            MainHelper::goBackIfNull($measureUnit);

            if ($measureUnit->products->isNotEmpty()) {
                return back()->withErrors(['product::admin.measure_units.cant_delete_has_products_associated']);
            }

            $measureUnit->delete();

            MeasureUnit::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        public function update($id, ProductAttributeUpdateRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $measureUnit = MeasureUnit::find($id);
            if (is_null($measureUnit)) {
                return redirect()->back()->withInput()->withErrors(['admin.common.record_not_found']);
            }

            $action->doSimpleUpdate(MeasureUnit::class, MeasureUnitTranslation::class, $measureUnit, $request);

            MeasureUnit::cacheUpdate();

            return redirect()->route('admin.measuring-units.index')->with('success-message', 'admin.common.successful_edit');
        }
        public function create()
        {
            if (is_null(Cache::get(CacheKeysHelper::$SHOP_MEASURE_UNITS_ADMIN))) {
                MeasureUnit::cacheUpdate();
            }

            return view('product::admin.settings.measure_units.create', [
                'languages' => LanguageHelper::getActiveLanguages(),
            ]);
        }
    }

<?php

namespace Modules\Shop\Http\Controllers\admin\ProductStocks;

use App\Actions\CommonControllerAction;
use App\Helpers\LanguageHelper;
use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Modules\Shop\Http\Requests\InternalSupplierStoreRequest;
use Modules\Shop\Http\Requests\InternalSupplierUpdateRequest;
use Modules\Shop\Models\Admin\Products\Stocks\InternalSupplier;
use Modules\Shop\Models\Admin\Products\Stocks\InternalSupplierTranslation;

class InternalSupplierController extends Controller
{
    public function index()
    {
        return view('shop::admin.products.stocks.internal_suppliers.index', [
            'suppliers' => InternalSupplier::archived(false)->get()
        ]);
    }

    public function archived()
    {
        $suppliers = InternalSupplier::archived(true)->get();

        return view('shop::admin.products.stocks.internal_suppliers.archived', compact('suppliers'));
    }
    public function store(InternalSupplierStoreRequest $request, CommonControllerAction $action)
    {
        $action->doSimpleCreate(InternalSupplier::class, $request);

        if ($request->has('submitaddnew')) {
            return redirect()->back()->with('success-message', 'admin.common.successful_create');
        }

        return redirect()->route('admin.product-stocks.internal-suppliers.index')->with('success-message', 'admin.common.successful_create');
    }
    public function edit($id)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        return view('shop::admin.products.stocks.internal_suppliers.edit', [
            'supplier'  => $supplier,
            'languages' => LanguageHelper::getActiveLanguages(),
            'suppliers' => InternalSupplier::with('translations')->orderBy('position')->get()
        ]);
    }
    public function active($id, $active)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        $supplier->update(['active' => $active]);

        return redirect()->route('admin.product-stocks.internal-suppliers.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function update($id, InternalSupplierUpdateRequest $request, CommonControllerAction $action)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        $request['position'] = $supplier->updatedPosition($request);
        $action->doSimpleUpdate(InternalSupplier::class, InternalSupplierTranslation::class, $supplier, $request);

        return redirect()->route('admin.product-stocks.internal-suppliers.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function create()
    {
        return view('shop::admin.products.stocks.internal_suppliers.create', [
            'languages' => LanguageHelper::getActiveLanguages(),
            'suppliers' => InternalSupplier::with('translations')->orderBy('position')->get()
        ]);
    }
    public function positionDown($id)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        $nextSupplier = InternalSupplier::where('position', $supplier->position + 1)->first();
        if (!is_null($nextSupplier)) {
            $nextSupplier->update(['position' => $nextSupplier->position - 1]);
            $supplier->update(['position' => $supplier->position + 1]);
        }

        return redirect()->route('admin.product-stocks.internal-suppliers.index')->with('success-message', 'admin.common.successful_edit');
    }

    public function positionUp($id)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        $prevSupplier = InternalSupplier::where('position', $supplier->position - 1)->first();
        if (!is_null($prevSupplier)) {
            $prevSupplier->update(['position' => $prevSupplier->position + 1]);
            $supplier->update(['position' => $supplier->position - 1]);
        }

        return redirect()->route('admin.product-stocks.internal-suppliers.index')->with('success-message', 'admin.common.successful_edit');
    }

    public function archive($id, $archived)
    {
        $supplier = InternalSupplier::find($id);
        WebsiteHelper::redirectBackIfNull($supplier);

        $supplier->update(['archived' => $archived]);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
}

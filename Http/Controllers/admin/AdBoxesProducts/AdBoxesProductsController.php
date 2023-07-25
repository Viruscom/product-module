<?php

namespace Modules\Shop\Http\Controllers\admin\AdBoxesProducts;

use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use App\Models\OtherSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Shop\Actions\ProductAdBoxAction;
use Modules\Shop\Entities\AdBoxProduct\AdBoxProduct;
use Modules\Shop\Entities\AdBoxProduct\AdBoxProductTranslation;
use Modules\Shop\Interfaces\ShopProductAdBoxInterface;

class AdBoxesProductsController extends Controller implements ShopProductAdBoxInterface
{
    public function index(ProductAdBoxAction $action)
    {
        $action->checkForNullCache();

        $adBoxesWaitingAction = AdBoxProduct::waitingAction()->orderByPosition('asc')->with('translations', 'product', 'product.translations')->get();
        $adBoxesFirstType     = AdBoxProduct::firstType()->orderByPosition('asc')->with('translations', 'product', 'product.translations')->get();

        $languages = LanguageHelper::getActiveLanguages();

        return view('shop::admin.adboxes_products.index', compact('adBoxesWaitingAction', 'adBoxesFirstType', 'languages'));
    }
    public function active($id, $active)
    {
        $adBox = AdBoxProduct::find($id);
        if (is_null($adBox)) {
            return redirect()->back()->withInput()->withErrors(['administration_messages.page_not_found']);
        }

        $adBox->update(['active' => $active]);

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function update($id, ProductAdBoxAction $action, Request $request)
    {
        $adBox = AdBoxProduct::find($id);
        if (is_null($adBox)) {
            return redirect()->back()->withInput()->withErrors(['administration_messages.page_not_found']);
        }

        $languages = LanguageHelper::getActiveLanguages();
        $data = $adBox->getUpdateData($request);

        foreach ($languages as $language) {
            $data[$language->code] = AdBoxProductTranslation::getUpdateData($language, $request);
        }

        $adBox->update($data);

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function deleteMultiple(Request $request, ProductAdBoxAction $action): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $action->deleteMultiple($request, AdBoxProduct::class);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }
    public function delete($id, ProductAdBoxAction $action)
    {
        $adBox = AdBoxProduct::find($id);
        if (is_null($adBox)) {
            return redirect()->back()->withInput()->withErrors(['administration_messages.page_not_found']);
        }

        $action->delete(AdBoxProduct::class, $adBox);

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }
    public function activeMultiple($active, Request $request)
    {
        if (!is_null($request->ids[0])) {
            $ids = array_map('intval', explode(',', $request->ids[0]));
            AdBoxProduct::whereIn('id', $ids)->update(['active' => $active]);
        }

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function positionDown($id)
    {
        $adBox = AdBoxProduct::find($id);
        if (is_null($adBox)) {
            return redirect()->back()->withInput()->withErrors(['administration_messages.page_not_found']);
        }

        $nextAdbox = AdBoxProduct::where('type', $adBox->type)->where('position', $adBox->position + 1)->first();
        if (!is_null($nextAdbox)) {
            $nextAdbox->update(['position' => $nextAdbox->position - 1]);
            $adBox->update(['position' => $adBox->position + 1]);
        }

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function positionUp($id)
    {
        $adBox = AdBoxProduct::find($id);
        if (is_null($adBox)) {
            return redirect()->back()->withInput()->withErrors(['administration_messages.page_not_found']);
        }

        $prevAdbox = AdBoxProduct::where('type', $adBox->type)->where('position', $adBox->position - 1)->first();
        if (!is_null($prevAdbox)) {
            $prevAdbox->update(['position' => $prevAdbox->position + 1]);
            $adBox->update(['position' => $adBox->position - 1]);
        }

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function returnToWaiting($id)
    {
        $adBox = AdBoxProduct::find($id);
        WebsiteHelper::redirectBackIfNull($adBox);

        $adBox->update(['type' => 0, 'active' => false]);

        return redirect()->route('admin.product-adboxes.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function edit($id)
    {
        $adBox = AdBoxProduct::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($adBox);

        return view('shop::admin.adboxes_products.edit', [
            'adBox'     => $adBox,
            'waitingAction' => AdBoxProduct::$WAITING_ACTION,
            'languages' => LanguageHelper::getActiveLanguages(),
        ]);
    }
}

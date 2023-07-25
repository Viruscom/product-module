<?php

namespace Modules\Shop\Http\Controllers;

use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use File;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Shop\Entities\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        if (is_null(Cache::get('shopAdmin'))) {
            Shop::cacheUpdate();
        }

        return view('shop::admin.index', ['changeVariable' => Cache::get('shopAdmin')]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $data      = Shop::getRequestData($request);
        $languages = LanguageHelper::getActiveLanguages();

        foreach ($languages as $language) {
            $data[$language->code] = ShopTranslation::getLanguageArray($language, $request);
        }
        $shop = Shop::create($data);
        if ($request->has('image')) {
            $shop->saveFile($request->image);
        }

        Shop::cacheUpdate();

        if ($request->has('submitaddnew')) {
            return redirect()->back()->with('success-message', 'shop::admin.common.successful_create');
        }

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_create');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $data              = [];
        $data['languages'] = LanguageHelper::getActiveLanguages();
        $data['shopAdmin'] = Cache::get('shopAdmin');
        $data['fileRules'] = Shop::getFileRulesForView();

        //        if (Module::has('ShopProductBrand')) {
        //            $data['brands'] = ShopProductBrand::active(true)->with('translations')->orderBy('position')->get();
        //        }

        return view('shop::admin.create', $data);
    }
    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function show($id)
    {
        return view('shop::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function edit($id)
    {
        $data         = [];
        $data['shop'] = Shop::find($id);
        if (is_null($data['shop'])) {
            return redirect()->back()->withInput()->withErrors(['shop::admin.common.record_not_found']);
        }

        $data['languages'] = LanguageHelper::getActiveLanguages();
        $data['shopAdmin'] = Cache::get('shopAdmin');
        $data['fileRules'] = Shop::getFileRulesForView();

        return view('shop::admin.edit', $data);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        if (file_exists($shop->imagePath())) {
            File::delete($shop->imagePath());
        }

        $shopesToUpdate = Shop::where('type', $shop->type)->where('position', '>', $shop->position)->get();
        $shop->delete();
        foreach ($shopsToUpdate as $shopToUpdate) {
            $shopToUpdate->update(['position' => $shopToUpdate->position - 1]);
        }

        return redirect()->back()->with('success-message', 'shop::admin.common.successful_delete');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $Shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        $data      = Shop::getRequestData($request);
        $languages = LanguageHelper::getActiveLanguages();

        foreach ($languages as $language) {
            $data[$language->code] = ShopTranslation::getLanguageArray($language, $request);
        }
        $shop->update($data);
        if ($request->has('image')) {
            $request->validate(['image' => Shop::getFileRule($shop->type)], Shop::getFileRuleMessage($shop->type));
            $Shop->saveFile($request->image);
        }

        Shop::cacheUpdate();

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_edit');
    }
    public function imgDeleteOneDimension($id): RedirectResponse
    {
        $shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        if (file_exists($shop->imagePath())) {
            unlink($shop->imagePath());
            $shop->update(['filename' => '']);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete_image');
        }

        return redirect()->back()->withErrors(['admin.common.image_not_found']);
    }

    public function deleteMultiple(Request $request): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $ids = array_map('intval', explode(',', $request->ids[0]));
            foreach ($ids as $id) {
                $shop = Shop::find($id);
                if (is_null($shop)) {
                    continue;
                }

                if (file_exists($shop->imagePath())) {
                    File::delete($shop->imagePath());
                }

                $shopesToUpdate = Shop::where('type', $shop->type)->where('position', '>', $shop->position)->get();
                $shop->delete();
                foreach ($shopesToUpdate as $shopToUpdate) {
                    $shopToUpdate->update(['position' => $shopToUpdate->position - 1]);
                }
            }

            return redirect()->back()->with('success-message', 'shop::admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }

    public function active($id, $active): RedirectResponse
    {
        $shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        $shop->update(['active' => $active]);

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_edit');
    }

    public function activeMultiple($active, Request $request): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $ids = array_map('intval', explode(',', $request->ids[0]));
            Shop::whereIn('id', $ids)->update(['active' => $active]);
        }

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_edit');
    }

    public function positionDown($id): RedirectResponse
    {
        $shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        $nextShop = Shop::where('type', $shop->type)->where('position', $shop->position + 1)->first();
        if (!is_null($nextShop)) {
            $nextShop->update(['position' => $nextShop->position - 1]);
            $shop->update(['position' => $shop->position + 1]);
        }

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_edit');
    }

    public function positionUp($id): RedirectResponse
    {
        $shop = Shop::find($id);
        MainHelper::goBackIfNull($shop);

        $prevShop = Shop::where('type', $shop->type)->where('position', $shop->position - 1)->first();
        if (!is_null($prevShop)) {
            $prevShop->update(['position' => $prevShop->position + 1]);
            $shop->update(['position' => $shop->position - 1]);
        }

        return redirect()->route('shop.index')->with('success-message', 'shop::admin.common.successful_edit');
    }
}

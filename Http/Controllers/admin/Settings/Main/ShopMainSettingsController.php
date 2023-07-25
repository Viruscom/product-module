<?php

namespace Modules\Product\Http\Controllers\admin\Settings\Main;

use App\Http\Controllers\Controller;
use App\Models\Settings\Post;
use App\Models\Settings\ShopSetting;
use Illuminate\Contracts\Support\Renderable;
use Modules\Product\Http\Requests\Admin\Settings\MainSettingsUpdateRequest;

class ShopMainSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $postSetting = Post::getSettings();
        if (is_null($postSetting)) {
            $postSetting = Post::storeEmptyRow();
        }

        return view('product::admin.settings.main.index', [
            'postSetting'  => $postSetting,
            'shopSettings' => ShopSetting::get()
        ]);
    }

    public function update(MainSettingsUpdateRequest $request)
    {
        foreach ($request->shopSettings as $key => $value) {
            ShopSetting::where('key', $key)->update(['value' => is_null($value) ? '' : $value]);
        }
        $postSetting = Post::first();

        $postSetting->update(['shop_orders_email' => $request->shop_orders_email]);
        Post::updateCache();

        return redirect()->route('admin.product.settings.index')->with('success-message', 'admin.common.successful_edit');
    }
}

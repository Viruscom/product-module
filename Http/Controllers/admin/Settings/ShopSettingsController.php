<?php

namespace Modules\Shop\Http\Controllers\admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ShopSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('shop::admin.settings.index');
    }
}

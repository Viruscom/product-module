<?php

namespace Modules\Product\Http\Controllers\admin\Settings;

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
        return view('product::admin.settings.index');
    }
}

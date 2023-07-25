<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        dd(session()->getId());
    }
}


//orders
//product_id
//price
//discount
//discounted_price
//dds
//end_price
//quantity

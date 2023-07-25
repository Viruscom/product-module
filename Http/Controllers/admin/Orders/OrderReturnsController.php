<?php

namespace Modules\Shop\Http\Controllers\admin\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shop\Entities\Orders\OrderReturn;

class OrderReturnsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('shop::admin.orders.returns.index', [
            'returns' => OrderReturn::with('order')->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function show($id)
    {
        $return = OrderReturn::whereId($id)->with(['products', 'order', 'order.products'])->first();
        if (is_null($return)) {
            return back()->withErrors(['Не е намерен записа']);
        }

        return view('shop::admin.orders.returns.show', compact('return'));
    }

    public function changeOrderReturnStatus(Request $request)
    {
        $return = OrderReturn::where('id', $request->return_id)->where('order_id', $request->order_id)->first();
        if (is_null($return)) {
            return trans('administration_messages.page_not_found');
        }

        $return->update(['status_id' => $request->status_id]);

        return trans('shop::admin.returned_products.order_return_status_successfully_changed');
    }
}

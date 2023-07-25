<?php

namespace Modules\Shop\Http\Controllers\admin\Settings\Deliveries;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Shop\Entities\Settings\Delivery;
use Validator;

class DeliveriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $deliveries = Delivery::orderBy('position', 'asc')->get();

        return view('shop::deliveries.index', ['deliveries' => $deliveries]);
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
        $delivery       = Delivery::findOrFail($id);
        $delivery->data = json_decode($delivery->data);

        return view($delivery->edit_view_path, ['delivery' => $delivery]);
    }
    public function updateState($id, $active)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->update(['active' => $active]);

        return redirect()->back()->with('success', __('Successful update'));
    }

    public function update($id, Request $request)
    {
        $delivery      = Delivery::findOrFail($id);
        $validatedData = Validator::make($request->all(), json_decode($delivery->validation_rules, true), json_decode($delivery->validation_messages, true), json_decode($delivery->validation_attributes, true))->validate();

        $dataArray = $delivery->generateData($request->all());
        $delivery->update(['position' => $delivery->position, 'active' => $delivery->active, 'data' => json_encode($dataArray)]);
        if (isset($request->position)) {
            $delivery->updatePosition($request->position);
        }

        return redirect()->route('deliveries.index')->with('success', __('Successful update'));
    }
    public function updatePosition($id, $position)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->updatePosition($position);

        return redirect()->back()->with('success', __('Successful update'));
    }
}

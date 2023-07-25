<?php

namespace Modules\Shop\Http\Controllers\admin\Settings\Payments;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shop\Entities\Settings\Payment;
use Validator;

class PaymentsController extends Controller
{

    public function index()
    {
        $payments = Payment::orderBy('position', 'asc')->get();

        return view('shop::payments.index', ['payments' => $payments]);
    }

    public function edit($id)
    {
        $payment = Payment::find($id);
        WebsiteHelper::redirectBackIfNull($payment);

        $payment->data = json_decode($payment->data);

        return view($payment->edit_view_path, ['payment' => $payment]);
    }
    public function updateState($id, $active)
    {
        $payment = Payment::find($id);
        WebsiteHelper::redirectBackIfNull($payment);
        $payment->update(['active' => $active]);

        return redirect()->back()->with('success-message', trans('admin.common.successful_edit'));
    }

    public function update($id, Request $request)
    {
        $payment = Payment::find($id);
        WebsiteHelper::redirectBackIfNull($payment);
        $validatedData = Validator::make($request->all(), json_decode($payment->validation_rules, true), json_decode($payment->validation_messages, true), json_decode($payment->validation_attributes, true))->validate();

        $dataArray = $payment->generateData($request->all());
        $payment->update(['position' => $payment->position, 'active' => $payment->active, 'data' => json_encode($dataArray)]);
        if (isset($request->position)) {
            $payment->updatePosition($request->position);
        }

        return redirect()->route('payments.index')->with('success-message', trans('admin.common.successful_edit'));
    }
    public function updatePosition($id, $position)
    {
        $payment = Payment::find($id);
        WebsiteHelper::redirectBackIfNull($payment);
        $payment->updatePosition($position);

        return redirect()->back()->with('success-message', trans('admin.common.successful_edit'));
    }
}

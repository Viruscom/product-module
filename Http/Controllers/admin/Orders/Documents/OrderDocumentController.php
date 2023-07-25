<?php

namespace Modules\Shop\Http\Controllers\admin\Orders\Documents;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use Modules\Shop\Entities\Orders\Order;
use Modules\Shop\Entities\Orders\OrderDocument;
use Modules\Shop\Http\Requests\OrderDocumentRequest;

class OrderDocumentController extends Controller
{
    public function store(OrderDocumentRequest $request)
    {

        $filename           = str_replace('.', '_', $request->file->getClientOriginalName());
        $document           = new OrderDocument();
        $document->name     = $request->name;
        $document->comment  = $request->comment;
        $document->order_id = $request->order_id;
        $document->filename = $filename . '.' . $request->file->getClientOriginalExtension();
        $document->save();

        $document->saveFile($request->file('file'), $document);

        $order = Order::find($document->order_id);
        if (!is_null($order)) {
            $order->history()->create(['activity_name' => trans('shop::admin.order_documents.added_document_with_name') . ': ' . $document->name . ', ' . trans('shop::admin.order_documents.comment') . ': ' . $document->comment . ', ' . trans('shop::admin.order_documents.name_of_file') . ': ' . $document->filename]);
        }

        if ($request->has('submitaddnew')) {
            return redirect()->back()->with('success-message', 'admin.common.successful_create');
        }

        return redirect()->route('admin.shop.orders.show', ['id' => $document->order_id])->with('success-message', 'admin.common.successful_create');
    }
    public function create($order_id)
    {
        $order = Order::find($order_id);
        if (is_null($order)) {
            return redirect()->back()->withInput()->withErrors(['shop::admin.orders.record_not_found']);
        }

        return view('shop::admin.orders.documents.create', compact('order'));
    }
    public function delete($order_id, $document_id)
    {
        $document = OrderDocument::find($document_id);
        if (is_null($document)) {
            return redirect()->back()->withErrors(['administration_messages.document_not_found']);
        }
        FileHelper::deleteFile($document->fullImageFilePath());
        $document->delete();

        $order = Order::find($document->order_id);
        if (!is_null($order)) {
            $order->history()->create(['activity_name' => trans('shop::admin.order_documents.deleted_document_with_name') . ': ' . $document->name . ', ' . trans('shop::admin.order_documents.comment') . ': ' . $document->comment . ', ' . trans('shop::admin.order_documents.name_of_file') . ': ' . $document->filename]);
        }

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }

    public function send($order_id, $document_id)
    {
        $document = OrderDocument::find($document_id);
        if (is_null($document)) {
            return redirect()->back()->withErrors(['shop::admin.order_documents.document_not_found']);
        }

        $document->sendMailDocument($document);

        $order = Order::find($document->order_id);
        if (!is_null($order)) {
            $order->history()->create(['activity_name' => trans('shop::admin.order_documents.sent_document_with_name') . ': ' . $document->name . ', ' . trans('shop::admin.order_documents.comment') . ': ' . $document->comment . ', ' . trans('shop::admin.order_documents.name_of_file') . ': ' . $document->filename]);
        }

        return redirect()->route('admin.shop.orders.show', ['id' => $document->order_id])->with('success-message', 'shop::admin.order_documents.document_successfully_sent');
    }
}

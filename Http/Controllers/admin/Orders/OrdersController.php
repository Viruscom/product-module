<?php

namespace Modules\Shop\Http\Controllers\admin\Orders;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Settings\ShopSetting;
use Illuminate\Http\Request;
use Modules\Shop\Entities\Orders\Order;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Entities\Settings\City;
use Modules\Shop\Entities\Settings\Delivery;
use Modules\Shop\Entities\Settings\Main\CountrySale;
use Modules\Shop\Entities\Settings\Payment;
use Modules\Shop\Http\Requests\OrderReturnAmountRequest;
use Modules\Shop\Models\Admin\Products\Product;

class OrdersController extends Controller
{
    public function index()
    {
        return view('shop::admin.orders.index', [
            'orders' => Order::orderBy('created_at', 'desc')->get()
        ]);
    }
    public function store($request)
    {
        $orderNumber = Order::max('id') + 1;
        //TODO: Send email to client with order info
        //        $order->sendMailOrderPlacedToClient();
    }
    public function edit($id)
    {
        $order = Order::where('id', $id)->with(
            'order_products',
            'order_products.product',
            'order_products.product.translations',
            'user',
            'payment',
            'delivery',
            'documents',
            'history',
            'returns'
        )->first();
        WebsiteHelper::redirectBackIfNull($order);

        $salesCountries = CountrySale::pluck('country_id')->toArray();

        return view('shop::admin.orders.edit', [
            'order'        => $order,
            'cities'       => City::whereIn('country_id', $salesCountries)->orderBy('name', 'asc')->get(),
            'products'     => Product::active(true)->with('translations')->get(),
            'clients'      => ShopRegisteredUser::where('active', true)->get(),
            'vrNumber'     => ShopSetting::where('key', 'virtual_receipt_number')->first(),
            'payments'     => Payment::all(),
            'myPosPayment' => Payment::where('type', 'mypos')->first()
        ]);
    }
    public function show($id)
    {
        $order = Order::where('id', $id)->with(
            'order_products',
            'order_products.product',
            'order_products.product.translations',
            'payment',
            'delivery',
            'documents',
            'history',
            'returns'
        )->first();
        WebsiteHelper::redirectBackIfNull($order);

        return view('shop::admin.orders.show', compact('order'));
    }
    public function changePaymentStatus($id, $request)
    {
        $order = Order::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($order);

        $order->update(['payment_status' => $request->payment_status_id]);
        $order->history()->create(['activity_name' => 'Статусът беше променен на: ' . $order->getReadablePaymentStatus()]);
        $order->sendMailPaymentStatusChanged();
    }
    public function update($id, $request)
    {
        $order = Order::find($id);
        WebsiteHelper::redirectBackIfNull($order);

        $order->update($order->getUpdateData($request));
        $order->updateProducts($request->only('products'));

        //TODO: uncomment this $order->sendMailOrderUpdated();

        return redirect()->route('admin.shop.orders')->with('success-message', 'admin.common.successful_edit');
    }
    public function create()
    {
        return view('shop::admin.orders.create', [
            'orderNumber'     => Order::max('id') + 1,
            'products'        => Product::with('translations', 'combinations')->get(),
            'clients'         => ShopRegisteredUser::where('active', true)->with('paymentAddresses', 'shipmentAddresses', 'companies')->get(),
            'saleCountries'   => CountrySale::with('country', 'country.cities')->get(),
            'paymentMethods'  => Payment::where('active', true)->orderBy('position', 'asc')->get(),
            'deliveryMethods' => Delivery::where('active', true)->orderBy('position', 'asc')->get()
        ]);
    }
    public function changeShipmentStatus($id, $request)
    {
        $order = Order::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($order);

        $order->update(['shipment_status' => $request->payment_status_id]);
        $order->history()->create(['activity_name' => 'Статусът беше променен на: ' . $order->getReadableShipmentStatus()]);
        $order->sendMailShipmentStatusChanged();
    }

    public function returnUpdate($id, OrderReturnAmountRequest $request)
    {
        $order = Order::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($order);

        if ($request->returned_amount > $order->grandTotalWithDiscountsVatAndDelivery()) {
            return redirect()->back()->withInput()->withErrors([trans('shop::admin.returned_products.amount_error')]);
        }

        $order->update([
                           'returned_amount' => $request->returned_amount,
                           'date_of_return'  => $request->date_of_return,
                           'type_of_return'  => $request->type_of_return,
                           'return_comment'  => $request->return_comment
                       ]);

        $order->history()->create(['activity_name' => 'Връщане на поръчка / пари беше променено на: Върната сума ' . $order->returned_amount . ' лв., дата на връщане на сумата ' . $order->date_of_return . ', начин на връщане на сумата ' . $order->type_of_return]);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function paymentUpdate()
    {
        //TODO: Send email to client with new payment
    }

    public function companyUpdate()
    {
        //TODO: Send email to client with new company
    }

    public function getProductByIdForOrder(Request $request)
    {
        $clientLevel = null;

        if ($request->has('client_id')) {
            $client      = ShopRegisteredUser::where('id', $request->client_id)->first();
            $clientLevel = is_null($client) ? 1 : $client->client_group_id;
        }

        if ($request->isProductCollection !== 'false') {
            //Get product collection here
            $collection = '66666666';

            return '999999';
        }

        $product = Product::where('id', $request->productID)->active(true)->isInStock()->with('translations')->first();
        if (is_null($product)) {
            return 'error';
        }

        return [
            'product'            => $product->toJson(),
            'productQuantity'    => $request->productQuantity,
            'productTranslation' => $product,
            'image'              => $product->getFileUrl(),
            'discounts'          => 0, //TODO: $product->getCalculatedDiscounts(),
            'priceWithDiscounts' => 0, //TODO: $product->getPriceWithDiscounts($clientLevel)
        ];
    }

    public function paymentStatusUpdate($id, Request $request)
    {
        $order = Order::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($order);

        $order->update(['payment_status' => $request->status]);
        $order->history()->create(['activity_name' => 'Промяна на статус на плащане: ' . $order->getReadablePaymentStatus()]);
        $order->sendMailPaymentStatusChanged();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function shipmentStatusUpdate($id, Request $request)
    {
        $order = Order::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($order);

        $order->update(['shipment_status' => $request->status]);
        $order->history()->create(['activity_name' => 'Промяна на статус на поръчката: ' . $order->getReadableShipmentStatus()]);
        $order->sendMailShipmentStatusChanged();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
}

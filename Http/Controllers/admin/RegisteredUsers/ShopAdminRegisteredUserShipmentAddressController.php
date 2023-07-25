<?php

namespace Modules\Shop\Http\Controllers\admin\RegisteredUsers;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shop\Entities\RegisteredUser\ShipmentAddresses\ShopShipmentAddress;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Entities\Settings\Country;
use Modules\Shop\Entities\Settings\Main\CountrySale;
use Modules\Shop\Entities\Settings\State;
use Modules\Shop\Http\Requests\Admin\RegisteredUser\ShipmentAddresses\AdminRegUserShipmentAddressStoreRequest;
use Modules\Shop\Http\Requests\Admin\RegisteredUser\ShipmentAddresses\AdminRegUserShipmentAddressUpdateRequest;

class ShopAdminRegisteredUserShipmentAddressController extends Controller
{
    public function store($id, AdminRegUserShipmentAddressStoreRequest $request)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->with('shipmentAddresses')->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        if ($request->has('is_default') && $request->is_default === 1 && $registeredUser->shipmentAddresses->isNotEmpty()) {
            $registeredUser->shipmentAddresses()->update(['is_default', false]);
        }

        if ($registeredUser->shipmentAddresses->isEmpty()) {
            $request['is_default'] = true;
        }

        $registeredUser->shipmentAddresses()->create($request->all());

        if ($request->has('submitaddnew')) {
            return redirect()->back()->with('success-message', 'administration_messages.successful_create');
        }

        return redirect()->route('admin.shop.registered-users.show', ['id' => $registeredUser->id])->with('success-message', 'admin.common.successful_create');
    }
    public function update($id, $address_id, AdminRegUserShipmentAddressUpdateRequest $request)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $address = ShopShipmentAddress::find($address_id);
        WebsiteHelper::redirectBackIfNull($address);

        $address->update($request->all());

        return redirect()->route('admin.shop.registered-users.show', ['id' => $registeredUser->id])->with('success-message', 'admin.common.successful_edit');
    }
    public function create($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $saleCountries = CountrySale::with('country')->get();

        return view('shop::admin.registered_users.shipment_addresses.create', compact('registeredUser', 'saleCountries'));
    }
    public function edit($id, $address_id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $address = ShopShipmentAddress::find($address_id);
        WebsiteHelper::redirectBackIfNull($address);

        $saleCountries = CountrySale::with('country')->get();

        return view('shop::admin.registered_users.shipment_addresses.edit', compact('registeredUser', 'address', 'saleCountries'));
    }
    public function setAsDefault($id, $address_id)
    {
        $address = ShopShipmentAddress::find($address_id);
        WebsiteHelper::redirectBackIfNull($address);

        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $registeredUser->shipmentAddresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success-message', 'admin.common.successful_edit');
    }
    public function delete($id, $address_id)
    {
        $registeredUser = ShopRegisteredUser::find($id);
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $address = ShopShipmentAddress::find($address_id);
        WebsiteHelper::redirectBackIfNull($address);

        $nextDefaultAddress = $registeredUser->shipmentAddresses()->where('id', '!=', $address->id)->first();
        if ($address->isDefault(true) && !is_null($nextDefaultAddress)) {
            $nextDefaultAddress->update(['is_default' => true]);
        }

        $address->delete();

        return back()->with('success-message', 'admin.common.successful_edit');
    }

    public function getStates($id, Request $request)
    {
        $country = Country::where('id', $request->country_id)->with('states')->first();
        if (is_null($country)) {
            return 'error';
        }

        return view('shop::admin.registered_users.partials.states_select', ['states' => $country->states]);
    }

    public function getCities($id, Request $request)
    {
        $state = State::where('id', $request->state_id)->with('cities')->first();
        if (is_null($state)) {
            return 'error';
        }

        return view('shop::admin.registered_users.partials.cities_select', ['cities' => $state->cities]);
    }
}

<?php

namespace Modules\Shop\Http\Controllers\admin\RegisteredUsers;

use App\Actions\CommonControllerAction;
use App\Helpers\MainHelper;
use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ClientRequest;
use App\Models\Shop_Models\Clients\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Http\Requests\Admin\RegisteredUser\AdminRegisteredUserStoreRequest;
use Modules\Shop\Http\Requests\Admin\RegisteredUser\AdminRegisteredUserUpdateRequest;

class ShopAdminRegisteredUsersController extends Controller
{
    public function index()
    {
        return view('shop::admin.registered_users.index', ['registeredUsers' => ShopRegisteredUser::with('orders')->get()]);
    }
    public function store(AdminRegisteredUserStoreRequest $request)
    {
        $data             = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['active']   = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }
        ShopRegisteredUser::create($data);

        return redirect()->route('admin.shop.registered-users.index')->with('success-message', 'admin.common.successful_create');
    }
    public function create()
    {
        return view('shop::admin.registered_users.create');
    }
    public function edit($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        return view('shop::admin.registered_users.edit', ['registeredUser' => $registeredUser]);
    }
    public function show($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->with('orders', 'favoriteProducts', 'paymentAddresses', 'shipmentAddresses', 'companies')->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        return view('shop::admin.registered_users.show', ['registeredUser' => $registeredUser]);
    }
    public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $action->activeMultiple(ShopRegisteredUser::class, $request, $active);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function changeActiveStatus($id, $active): RedirectResponse
    {
        $registeredUser = ShopRegisteredUser::find($id);
        MainHelper::goBackIfNull($registeredUser);

        $registeredUser->update(['active' => $active]);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function update($id, AdminRegisteredUserUpdateRequest $request)
    {
        $registeredUser = ShopRegisteredUser::find($id);
        WebsiteHelper::redirectBackIfNull($registeredUser);
        $registeredUser->update($registeredUser->getUpdateData($request));

        return redirect()->route('admin.shop.registered-users.index')->with('success-message', 'admin.common.successful_edit');
    }

    public function favoriteProductsIndex()
    {

    }

    public function getAjaxClientById(Request $request)
    {
        $client = ShopRegisteredUser::where('id', $request->client_id)->with('shipmentAddresses', 'paymentAddresses', 'companies')->first();
        if (is_null($client)) {
            return response()->json(['error' => ['client_not_found' => 'Няма такъв клиент']]);
        }

        return $client;
    }

    public function getClientGroupById(Request $request)
    {
        $clientGroupId = ShopRegisteredUser::where('id', $request->client_id)->first()->group_id;

        return ($clientGroupId) ?? 1;
    }
}

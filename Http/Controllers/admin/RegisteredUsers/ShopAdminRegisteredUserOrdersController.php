<?php

namespace Modules\Shop\Http\Controllers\admin\RegisteredUsers;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shop\Entities\RegisteredUser\Firms\Company;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Http\Requests\Admin\RegisteredUser\Companies\AdminRegUserCompanyStoreRequest;

class ShopAdminRegisteredUserOrdersController extends Controller
{
    public function index($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->with('orders')->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        return view('shop::admin.registered_users.orders.index', ['registeredUser' => $registeredUser, 'orders' => $registeredUser->orders]);
    }
    
    public function store($id, AdminRegUserCompanyStoreRequest $request)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->with('companies')->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        if ($request->has('is_default') && $request->is_default === true && $registeredUser->companies->isNotEmpty()) {
            $registeredUser->companies()->update(['is_default', false]);
        }

        if ($registeredUser->companies->isEmpty()) {
            $request['is_default'] = true;
        }

        $registeredUser->companies()->create($request->all());

        return redirect()->route('admin.shop.registered-users.show', ['id' => $registeredUser->id])->with('success-message', 'admin.common.successful_create');
    }
    public function update($id, $company_id, Request $request)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $company = Company::find($company_id);
        WebsiteHelper::redirectBackIfNull($company);

        $company->update($request->all());

        return redirect()->route('admin.shop.registered-users.show', ['id' => $registeredUser->id])->with('success-message', 'admin.common.successful_edit');
    }
    public function create($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        return view('shop::admin.registered_users.firms.create', compact('registeredUser'));
    }
    public function edit($id, $company_id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $company = Company::find($company_id);
        WebsiteHelper::redirectBackIfNull($company);

        return view('shop::admin.registered_users.firms.edit', compact('registeredUser', 'company'));
    }
    public function setAsDefault($id, $company_id)
    {
        $company = Company::find($company_id);
        WebsiteHelper::redirectBackIfNull($company);

        $registeredUser = ShopRegisteredUser::where('id', $id)->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $registeredUser->companies()->update(['is_default' => false]);
        $company->update(['is_default' => true]);

        return back()->with('success-message', 'admin.common.successful_edit');
    }
    public function delete($id, $company_id)
    {
        $registeredUser = ShopRegisteredUser::find($id);
        WebsiteHelper::redirectBackIfNull($registeredUser);

        $company = Company::find($company_id);
        WebsiteHelper::redirectBackIfNull($company);

        $nextDefaultCompany = $registeredUser->companies()->where('id', '!=', $company->id)->first();
        if ($company->isDefault(true) && !is_null($nextDefaultCompany)) {
            $nextDefaultCompany->update(['is_default' => true]);
        }

        $company->delete();

        return back()->with('success-message', 'admin.common.successful_edit');
    }
}

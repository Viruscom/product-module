<?php

namespace Modules\Shop\Http\Controllers\Front\RegisteredUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Http\Requests\Front\RegisteredUser\PersonalDataUpdateRequest;

class RegisteredUserAccountController extends Controller
{
    public function dashboard()
    {
        return view('shop::front.registered_users.profile.dashboard', ['registeredUser' => Auth::guard('shop')->user()]);
    }

    public function personalData()
    {
        return view('shop::front.registered_users.profile.personal_data', ['registeredUser' => Auth::guard('shop')->user()]);
    }

    public function update($languageSlug, $id, PersonalDataUpdateRequest $request)
    {
        if (Auth::guard('shop')->user()->id != $id) {
            return back()->withErrors(['shop::front.registered_user_profile.user_not_the_same']);
        }
        Auth::guard('shop')->user()->update($request->all());

        return back()->withInput()->with('success-message', 'admin.common.successful_edit');
    }

    public function getFavoriteProducts()
    {
        return Auth::guard('shop')->user()->favoriteProducts()->get();
    }
}

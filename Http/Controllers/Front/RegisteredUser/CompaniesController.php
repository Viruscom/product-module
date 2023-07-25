<?php

namespace Modules\Shop\Http\Controllers\Front\RegisteredUser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    public function dashboard()
    {
        return view('shop::front.registered_users.profile.dashboard', ['registeredUser' => Auth::guard('shop')->user()]);
    }

    public function personalData()
    {
        return view('shop::front.registered_users.profile.personal_data', ['registeredUser' => Auth::guard('shop')->user()]);
    }
}

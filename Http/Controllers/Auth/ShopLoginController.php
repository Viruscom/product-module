<?php

namespace Modules\Shop\Http\Controllers\Auth;


use App\Helpers\LanguageHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopLoginController extends ShopRegisterController
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (!is_null(Auth::guard('shop')->user())) {
            return redirect()->route('shop.dashboard', ['languageSlug' => LanguageHelper::getCurrentLanguage()->code]);
        }

        return view('shop::auth.login');
    }
    protected function guard()
    {
        return Auth::guard('shop');
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('shop.login', ['languageSlug' => LanguageHelper::getCurrentLanguage()->code]);
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('shop.dashboard', ['languageSlug' => LanguageHelper::getCurrentLanguage()->code]);
    }
}

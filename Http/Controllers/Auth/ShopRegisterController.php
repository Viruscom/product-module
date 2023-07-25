<?php

namespace Modules\Shop\Http\Controllers\Auth;

use App\Helpers\LanguageHelper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Modules\Shop\Http\Controllers\ShopRegisteredUserController;

class ShopRegisterController extends ShopRegisteredUserController
{
    public function showRegistrationForm()
    {
        return view('shop::auth.register');
    }

    public function register(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return redirect()->route('shop.dashboard', ['languageSlug' => LanguageHelper::getCurrentLanguage()->code]);
    }
    protected function rules()
    {
        return [
            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required',
            'email'      => 'required|email|unique:shop_registered_users',
            'password'   => 'required|min:8|confirmed',
        ];
    }
    protected function validationErrorMessages()
    {
        return [
            'first_name.required' => __('shop::front.login.first_name_required'),
            'last_name.required'  => __('shop::front.login.last_name_required'),
            'phone.required'      => __('shop::front.login.phone_required'),
            'email.required'      => __('shop::front.login.email_required'),
            'email.email'         => __('shop::front.login.email_email'),
            'email.unique'        => __('shop::front.login.email_unique'),
            'password.required'   => __('shop::front.login.password_required'),
            'password.min'        => __('shop::front.login.password_min'),
            'password.confirmed'  => __('shop::front.login.password_confirmed'),
        ];
    }
    protected function create(array $data)
    {
        return ShopRegisteredUser::create([
                                              'first_name' => $data['first_name'],
                                              'last_name'  => $data['last_name'],
                                              'phone'      => $data['phone'],
                                              'email'      => $data['email'],
                                              'password'   => Hash::make($data['password']),
                                              'active'     => 0
                                          ]);
    }
}

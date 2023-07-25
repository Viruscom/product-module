<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;
use Password;
use Validator;

class ShopRegisteredUserController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('shop::login');
    }
    public function showForgotPasswordForm()
    {
        return view('shop::forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(
                ['email' => __($response)]
            );
    }
    protected function broker()
    {
        return Password::broker('shop_users');
    }
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('shop.login')->with('status', __($response))
            : back()->withErrors(
                ['email' => [__($response)]]
            );
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('shop.login');
    }
    protected function guard()
    {
        return Auth::guard('shop');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:shop_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    protected function create(array $data)
    {
        return ShopRegisteredUser::create([
                                              'name'     => $data['name'],
                                              'email'    => $data['email'],
                                              'password' => Hash::make($data['password']),
                                          ]);
    }
    protected function redirectTo()
    {
        return route('shop.dashboard');
    }
    //    public function showRegistrationForm()
    //    {
    //        return view('shop::front.login.register');
    //    }
    //
    //    public function register(Request $request)
    //    {
    //        $validatedData = $request->validate([
    //                                                'name'     => 'required|string|max:255',
    //                                                'email'    => 'required|string|email|max:255|unique:shop_registered_users',
    //                                                'password' => 'required|string|min:8|confirmed',
    //                                            ]);
    //
    //        $user           = new ShopRegisteredUser();
    //        $user->name     = $validatedData['name'];
    //        $user->email    = $validatedData['email'];
    //        $user->password = bcrypt($validatedData['password']);
    //        $user->save();
    //
    //        Auth::login($user);
    //
    //        return redirect()->intended('/');
    //    }
    //    public function login(Request $request)
    //    {
    //        $credentials = $request->validate([
    //                                              'email'    => 'required|string|email',
    //                                              'password' => 'required|string',
    //                                          ]);
    //
    //        if (Auth::attempt($credentials)) {
    //            $request->session()->regenerate();
    //
    //            return redirect()->intended('/');
    //        }
    //
    //        return back()->withErrors([
    //                                      'email' => 'The provided credentials do not match our records.',
    //                                  ]);
    //    }
    //    public function showLoginForm()
    //    {
    //        return view('shop::front.login.login');
    //    }
}

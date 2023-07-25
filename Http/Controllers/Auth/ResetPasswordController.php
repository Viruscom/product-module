<?php

namespace Modules\Shop\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        return view('shop::auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}

<?php

namespace Modules\Shop\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Shop\Http\Controllers\ShopRegisteredUserController;

class ShopVerificationController extends ShopRegisteredUserController
{

    public function show(Request $request)
    {
        return $request->user('shop')->hasVerifiedEmail()
            ? redirect()->route('shop.dashboard')
            : view('shop::auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function verify(Request $request)
    {
        if ($request->user('shop')->hasVerifiedEmail()) {
            return redirect()->route('shop.dashboard');
        }

        if ($request->user('shop')->markEmailAsVerified()) {
            event(new Verified($request->user('shop')));
        }

        return redirect()->route('shop.dashboard')->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user('shop')->hasVerifiedEmail()) {
            return redirect()->route('shop.dashboard');
        }

        $request->user('shop')->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}

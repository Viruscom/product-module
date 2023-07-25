<?php

namespace Modules\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCookieMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!isset($_COOKIE['sbuuid'])) {
            $sbuuid = uniqid(uniqid(uniqid(uniqid('', true) . "-", true) . "-", true) . "-", true);
            setcookie("sbuuid", $sbuuid, time() + (86400 * 365), "/");
        } else {
            $sbuuid = $_COOKIE['sbuuid'];
            setcookie("sbuuid", $sbuuid, time() + (86400 * 365), "/");
        }

        $request->headers->set('sbuuid', $sbuuid);
        return $next($request);
    }
}

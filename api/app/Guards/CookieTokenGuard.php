<?php

namespace App\Guards;

use App\Services\CookieManager;
use Laravel\Sanctum\Guard;
use Illuminate\Http\Request;

class CookieTokenGuard extends Guard
{
    protected function getTokenFromRequest(Request $request)
    {
        // First try the standard bearer token
        if ($token = $request->bearerToken()) {
            return $token;
        }

        // Then try the cookie
        if ($token = $request->cookie(CookieManager::accessTokenCookieName())) {
            return $token;
        }

        return null;
    }
}

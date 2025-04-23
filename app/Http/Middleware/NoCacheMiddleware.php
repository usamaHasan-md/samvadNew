<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class NoCacheMiddleware
{
    public function handle($request, Closure $next)
    {
        // Guard wise Session Cookie set karna
        if (Auth::guard('admin')->check()) {
            Config::set('session.cookie', env('ADMIN_SESSION_COOKIE', 'admin_session'));
        } elseif (Auth::guard('vendor')->check()) {
            Config::set('session.cookie', env('VENDOR_SESSION_COOKIE', 'vendor_session'));
        } elseif (Auth::guard('fieldagent')->check()) {
            Config::set('session.cookie', env('FIELDAGENT_SESSION_COOKIE', 'fieldagent_session'));
        } else {
            Config::set('session.cookie', env('SESSION_COOKIE', 'laravel_session'));
        }

        // No-cache headers
        $response = $next($request);
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}

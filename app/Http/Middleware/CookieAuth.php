<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieAuth
{
    /**
     * Check incoming request to see if auth cookie set
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Cookie::get('tenant_id')) {
            return $next($request);
        } else {
            return redirect()->route('login');
        }
    }
}

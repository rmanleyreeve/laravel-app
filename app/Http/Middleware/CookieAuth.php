<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if(isset($_COOKIE['tenant_id'])) {
            return $next($request);
        } else {
            return redirect()->route('login');
        }
    }
}

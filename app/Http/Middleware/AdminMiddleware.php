<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::check()) {
                return redirect()->guest('admin/adminlogin');
        }
        if ( !$request->user()->isAdmin($request->user()) ) {
            return redirect('/user/dashboard');
        }
        return $next($request);
    }
}

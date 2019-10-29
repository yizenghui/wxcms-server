<?php

namespace Encore\Admin\Registration\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class AdminGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return redirect(config('admin.route.prefix'));
        }

        return $next($request);
    }
}
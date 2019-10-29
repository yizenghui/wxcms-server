<?php

namespace Encore\Admin\Registration\Http\Middleware;

use Closure;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Registration\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        if (! Admin::user() ||
            (Admin::user() instanceof MustVerifyEmail &&
            ! Admin::user()->hasVerifiedEmail())) {

            return $request->expectsJson()
                    ? abort(403, __('registration.email_not_verified'))
                    : Redirect::route($redirectToRoute ?: 'admin.verification.notice');
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = config('admin.auth.excepts', []);

        $excepts = array_merge($excepts, [
            'auth/email/verify',
            'auth/email/verify/*',
            'auth/email/resend',
        ]);

        return collect($excepts)->unique()
            ->map('admin_base_path')
            ->contains(function ($except) use ($request) {
                if ($except !== '/') {
                    $except = trim($except, '/');
                }

                return $request->is($except);
            });
    }
}

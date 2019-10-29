<?php

namespace Encore\Admin\Registration\Http\Middleware;

use Illuminate\Support\Facades\URL;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (url()->hasValidSignature($request)) {
            return $next($request);
        }

        abort(403, __('registration.invalid_signature'));
    }
}

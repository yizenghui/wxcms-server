<?php

namespace Encore\Admin\Multitenancy\Middleware;

use Illuminate\Http\Request;

class MultiSession
{
    public function handle(Request $request, \Closure $next, $key, $value)
    {
        config(["session.$key" => $value]);

        return $next($request);
    }
}

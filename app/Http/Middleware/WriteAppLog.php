<?php

namespace App\Http\Middleware;

use Closure;
use Vinkla\Hashids\Facades\Hashids;

class WriteAppLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        ( new \App\Repositories\AppRepository )->log();
        return $next($request);
    }
}
    
<?php

namespace App\Http\Middleware;

use Closure;
use Vinkla\Hashids\Facades\Hashids;

class CheckTenancy
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
        
        $api_token = $request->server('HTTP_API_TOKEN');//api_token

        $ids = Hashids::decode($api_token);
        // todo 检查参数的合法性

        $appid = intval($ids[0]);

        // todo 默认值
        if(!$appid) $appid = 1; 

        $request->attributes->add(compact('appid'));//添加参数

        return $next($request);
    }
}
    
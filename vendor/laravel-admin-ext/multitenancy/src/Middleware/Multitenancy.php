<?php

namespace Encore\Admin\Multitenancy\Middleware;

class Multitenancy
{
    public function handle($request, \Closure $next, ...$args)
    {
        $this->overrideConfig($args[0]);

        return $next($request);
    }

    protected function overrideConfig($name)
    {
        $config = require config("admin.extensions.multitenancy.$name");

        config(['admin' => $config]);

        config(array_dot(config('admin.auth', []), 'auth.'));
    }
}
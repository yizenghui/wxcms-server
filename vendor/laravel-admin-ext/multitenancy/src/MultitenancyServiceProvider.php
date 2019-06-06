<?php

namespace Encore\Admin\Multitenancy;

use Encore\Admin\Multitenancy\Console\InstallTenancyCommand;
use Illuminate\Support\ServiceProvider;

class MultitenancyServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Multitenancy $extension)
    {
        if (!Multitenancy::boot()) {
            return;
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'laravel-admin-tenancy-config');
        }

        $this->bootMultitenancy(config('admin.extensions.multitenancy', []));
    }

    /**
     * Bootstrap multitenancy.
     *
     * @param array $configs
     */
    protected function bootMultitenancy(array $configs)
    {
        foreach ($configs as $name => $config) {
            if (file_exists($config)) {
                Multitenancy::register($name, require $config);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerCommands();

        $this->registerMiddleware();
    }

    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->commands([
            InstallTenancyCommand::class,
        ]);
    }

    /**
     * Register route middleware.
     */
    protected function registerMiddleware()
    {
        app('router')->aliasMiddleware('multitenancy', Middleware\Multitenancy::class);
        app('router')->aliasMiddleware('multi-session', Middleware\MultiSession::class);
    }
}

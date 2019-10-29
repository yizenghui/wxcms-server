<?php

namespace Encore\Admin\Registration;

use Encore\Admin\Registration\Http\Controllers\SettingController;
use Encore\Admin\Registration\Http\Controllers\UserController;
use Encore\Admin\Registration\Http\Middleware\AdminGuest;
use Encore\Admin\Registration\Http\Middleware\ValidateSignature;
use Encore\Admin\Registration\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RegistrationServiceProvider extends ServiceProvider
{
    protected $routeMiddleware = [
        'admin.signed'   => ValidateSignature::class,
        'admin.verified' => EnsureEmailIsVerified::class,
        'admin.guest'    => AdminGuest::class,
    ];

    protected $permissionExcepts = [
        'auth/login',
        'auth/logout',
        'auth/register',
        'auth/password/reset',
        'auth/password/email',
        'auth/password/reset/*',
    ];

    /**
     * {@inheritdoc}
     */
    public function boot(Registration $extension)
    {
        if (!Registration::boot()) {
            return;
        }

        $this->loadViewsFrom($extension->views(), 'registration');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config' => config_path('admin'),
                __DIR__ . '/../resources/lang' => resource_path('lang'),
                __DIR__.'/../database/migrations' => database_path('migrations')
            ], 'registration');
        }

        $this->registerRoutes();

        $this->overrideAdminConfiguration();

        Registration::macroUrlGenerator();
    }

    protected function overrideAdminConfiguration()
    {
        $excepts = collect(config('admin.auth.excepts', []))->merge($this->permissionExcepts);

        $passwordResetsTable = config('admin.database.password_resets_table', 'admin_password_resets');

        config([
            'admin.auth.excepts'         => $excepts->toArray(),
            // 'auth.providers.admin.model' => User::class,
            'auth.passwords.admin'       => [
                'provider' => 'admin',
                'table'    => $passwordResetsTable,
                'expire'   => 60,
            ],
        ]);
    }

    public function registerRoutes()
    {
        $attributes = [
            'prefix'     => config('admin.route.prefix'),
            'middleware' => ['web', 'admin.auth'],
        ];

        Route::group($attributes, __DIR__ . '/../routes/web.php');

        Registration::routes(function ($router) {
            // $router->get('auth/setting', SettingController::class.'@getSetting')->name('admin.setting');
            // $router->put('auth/setting', SettingController::class.'@putSetting');
            // $router->resource('auth/users', UserController::class);
        });
    }

    public function register()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }
}
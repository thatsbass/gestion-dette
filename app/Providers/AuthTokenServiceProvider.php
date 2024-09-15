<?php

namespace App\Providers;

use App\Contracts\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class AuthTokenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, function ($app) {
            $driver = config("auth_service.driver");
            $driverConfig = config("auth_service.drivers.{$driver}");
            $driverClass = $driverConfig["class"];
            return new $driverClass($driverConfig["config"]);
        });
    }

    public function boot()
    {
        //
    }
}

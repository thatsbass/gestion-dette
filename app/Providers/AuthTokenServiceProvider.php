<?php

namespace App\Providers;

use App\Services\AuthTokenServiceInterface;
use App\Services\PassportAuthTokenService;
use App\Services\SanctumAuthTokenService;
use Illuminate\Support\ServiceProvider;

class AuthTokenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthTokenServiceInterface::class, function ($app) {
            if (config('auth.token_driver') === 'passport') {
                return new PassportAuthTokenService();
            }
            
            return new SanctumAuthTokenService();
        });
    }

    public function boot()
    {
        //
    }
}

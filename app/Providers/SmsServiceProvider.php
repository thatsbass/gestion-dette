<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Messaging\MessagingServiceInterface;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MessagingServiceInterface::class, function ($app) {
            $driver = config('messaging.default');
            $config = config("messaging.drivers.{$driver}.config");
            $class = config("messaging.drivers.{$driver}.class");

            return new $class(...array_values($config));
        });
    }
}

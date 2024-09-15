<?php

namespace App\Providers;

use App\Services\Archive\ArchiveServiceInterface;
use Illuminate\Support\ServiceProvider;

class ArchiveServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ArchiveServiceInterface::class, function ($app) {
            $driver = config("archive.default");
            $config = config("archive.drivers.{$driver}.config");
            $class = config("archive.drivers.{$driver}.class");

            return new $class(...array_values($config));
        });
    }
}

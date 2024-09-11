<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Services\Interfaces\ClientServiceInterface;
use App\Services\ClientService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
// use App\Observers\UserObserver;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     //
    }
}

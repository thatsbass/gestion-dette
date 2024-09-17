<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Services\Interfaces\ClientServiceInterface;
use App\Services\ClientService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\ArticleService;
use App\Repositories\DetteRepository;
use App\Repositories\PaiementRepository;
use App\Repositories\Interfaces\DetteRepositoryInterface;
use App\Repositories\Interfaces\PaiementRepositoryInterface;
use App\Services\Interfaces\DemandeServiceInterface;
use App\Services\DemandeService;
use App\Repositories\Interfaces\DemandeRepositoryInterface;
use App\Repositories\DemandeRepository;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(
            ArticleRepositoryInterface::class,
            ArticleRepository::class
        );
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
        $this->app->bind(
            DetteRepositoryInterface::class,
            DetteRepository::class
        );
        $this->app->bind(
            PaiementRepositoryInterface::class,
            PaiementRepository::class
        );
        $this->app->bind(
            DemandeServiceInterface::class,
            DemandeService::class
        );
        $this->app->bind(
            DemandeRepositoryInterface::class,
            DemandeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

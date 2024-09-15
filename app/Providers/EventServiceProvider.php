<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserPhotoUploaded;
use App\Listeners\HandleUserPhotoUpload;
use App\Listeners\HandleEmailListener;
use App\Events\NotificationEvent;
use App\Listeners\NotificationListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserPhotoUploaded::class => [
            HandleUserPhotoUpload::class,
            HandleEmailListener::class,
        ],
        NotificationEvent::class => [NotificationListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}

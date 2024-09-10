<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Events\ClientCreatedEvent;
use App\Listeners\HandleClientCreation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        ClientCreatedEvent::class => [
            HandleClientCreation::class,
        ],
    ];
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        parent::boot();
    }
}

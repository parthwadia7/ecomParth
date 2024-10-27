<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AuthServiceProvider.php
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];
    // app/Providers/EventServiceProvider.php
    protected $listen = [
        'App\Events\OrderPlaced' => [
            'App\Listeners\SendOrderConfirmation',
        ],
        'App\Events\OrderShipped' => [
            'App\Listeners\NotifyUserOrderShipped',
        ],
    ];
    
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

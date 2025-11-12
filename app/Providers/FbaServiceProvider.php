<?php

namespace App\Providers;

use App\Services\FbaService;
use App\ShippingServiceInterface;
use Illuminate\Support\ServiceProvider;

class FbaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ShippingServiceInterface::class, FbaService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

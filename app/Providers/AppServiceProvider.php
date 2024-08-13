<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\AnnonceCreee;
use App\Listeners\EnvoyerNotificationAnnonce;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
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

<?php

namespace App\Providers;

use App\Models\Entite;
use App\Observers\EntiteObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);
        Entite::observe(EntiteObserver::class);
        // URL::forceScheme('https');
    }
}

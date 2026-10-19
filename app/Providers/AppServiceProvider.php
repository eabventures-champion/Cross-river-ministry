<?php

namespace App\Providers;

use App\Services\CartService;
use App\Services\MediaService;
use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
        $this->app->singleton(SeoService::class);
        $this->app->singleton(CartService::class);
        $this->app->singleton(MediaService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}

<?php

namespace Roomies\Geolocatable;

use Illuminate\Support\ServiceProvider;

class GeolocatableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/geolocatable.php', 'geolocatable'
        );

        $this->app->bind(
            abstract: 'geolocatable',
            concrete: fn ($app) => new Manager($app),
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/geolocatable.php' => config_path('geolocatable.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\DownloadMaxmindDatabase::class,
            ]);
        }
    }
}

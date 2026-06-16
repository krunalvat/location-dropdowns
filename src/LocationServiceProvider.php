<?php

namespace Krunalvatvani\LocationDropdowns;

use Illuminate\Support\ServiceProvider;
use Krunalvatvani\LocationDropdowns\Console\Commands\SyncLocationsCommand;

class LocationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Automatically merge the package config with the application's config
        $this->mergeConfigFrom(
            __DIR__.'/../config/location.php', 'location'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // 1. Publish Configuration File
        $this->publishes([
            __DIR__.'/../config/location.php' => config_path('location.php'),
        ], 'location-config');

        // 2. Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'location-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncLocationsCommand::class,
            ]);
        }
    }
}

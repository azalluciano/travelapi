<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\FileBasedMaintenanceMode;
use Illuminate\Encryption\Encrypter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            MaintenanceMode::class,
            FileBasedMaintenanceMode::class
        );

        // Une solution plus directe pour l'Encrypter
        $this->app->singleton('encrypter', function ($app) {
            $key = base64_decode(substr(env('APP_KEY'), 7));
            return new Encrypter($key, 'AES-256-CBC');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

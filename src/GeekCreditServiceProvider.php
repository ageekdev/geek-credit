<?php

namespace Ageekdev\GeekCredit;

use Illuminate\Support\ServiceProvider;

class GeekCreditServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->loadMigrationsFrom([
            dirname(__DIR__).'/database',
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/geek-credit.php', 'geek-credit');

        $this->app->singleton('geek-credit', function () {
            return new GeekCredit();
        });
    }

    /**
     * Bootstrap the application services
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/geek-credit.php' => config_path('geek-credit.php'),
        ], 'config');
    }
}

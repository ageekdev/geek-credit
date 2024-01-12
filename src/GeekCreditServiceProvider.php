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
        $this->configure();
    }

    /**
     * Bootstrap the application services
     */
    public function boot(): void
    {
        $this->registerPublishing();
    }

    /**
     * Setup the configuration.
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/geek-credit.php', 'geek-credit'
        );
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/geek-credit.php' => $this->app->configPath('geek-credit.php'),
            ], 'geek-credit-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'geek-credit-migrations');
        }
    }
}

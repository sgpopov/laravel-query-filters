<?php

namespace SGP\QueryFilters;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/query-filters.php' => config_path('query-filters.php'),
        ], 'config');
    }
}

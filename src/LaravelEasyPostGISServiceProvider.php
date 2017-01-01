<?php

namespace Ajthinking\LaravelEasyPostGIS;

use Illuminate\Support\ServiceProvider;

class LaravelEasyPostGISServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Ajthinking\LaravelEasyPostGIS\Commands\PostgisifyCommand'        
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
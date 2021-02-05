<?php

namespace Ogecut\ContentApi;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Ogecut\ContentApi\Admin\Resources\ContentBlockItemResource;
use Ogecut\ContentApi\Admin\Resources\ContentBlockResource;
use Ogecut\ContentApi\Admin\Resources\ContentGroupResource;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        $this->app->booted(function () {
            $this->routes();
        });
        
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        
        Nova::serving(function (ServingNova $event) {
            Nova::script('content-api', __DIR__.'/../dist/js/field.js');
            Nova::style('content-api', __DIR__.'/../dist/css/field.css');
        });
        
        Nova::resources([
            ContentGroupResource::class,
            ContentBlockResource::class,
            ContentBlockItemResource::class,
        ]);
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/content-api.php', 'content-api');
    }
    
    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }
        
        Route::middleware([
            //'nova'
        ])
            ->prefix('api/content-api')
            ->group(__DIR__.'/../routes/api.php');
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/content-api.php' => config_path('content-api.php'),
        ], 'content-api.config');
        
        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/forms-storage'),
        ], 'formsstorage.views');*/
        
        // Registering package commands.
        // $this->commands([]);
    }
}

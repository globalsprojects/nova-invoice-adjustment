<?php

namespace GlobalsProjects\InvoiceAdjustment;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use GlobalsProjects\InvoiceAdjustment\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{

    private $config = 'invoiceadjustment';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'invoice-adjustment');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'invoice-adjustment');

        $this->app->booted(function () {
            $this->routes();
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/'.$this->config.'.php' => base_path('config/'.$this->config.'.php'),
            ], 'config');
        }

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/invoice-adjustment'),
        ], 'lang');

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/invoice-adjustment')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.$this->config.'.php', $this->config);
    }
}

<?php

namespace Whitecube\NovaPage;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Laravel\Nova\Nova;
use Whitecube\NovaPage\Http\Middleware\Authorize;

class NovaPageToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register routes after Nova has registered its routes
        // Using booted() ensures all service providers have finished booting
        $this->app->booted(function () {
            $this->registerRoutes();
            $this->registerResources();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register nova-page resources with Nova.
     * This ensures they're available even if the tool boot doesn't run
     * (e.g., when the user is not yet authenticated).
     *
     * @return void
     */
    protected function registerResources()
    {
        Nova::resources([
            config(
                "novapage.default_page_resource",
                \Whitecube\NovaPage\Pages\PageResource::class,
            ),
            config(
                "novapage.default_option_resource",
                \Whitecube\NovaPage\Pages\OptionResource::class,
            ),
        ]);
    }

    protected function registerRoutes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        /** @var Router $router */
        $router = $this->app["router"];

        // Get the current routes before adding ours
        $existingRoutes = $router->getRoutes()->getRoutes();

        // Clear all routes
        $router->setRoutes(new \Illuminate\Routing\RouteCollection());

        // Register our routes FIRST (so they have priority)
        Route::prefix("nova-api")
            ->middleware(["nova", Authorize::class])
            ->group(__DIR__ . "/../routes/api.php");

        // Re-add all existing routes
        foreach ($existingRoutes as $route) {
            $router->getRoutes()->add($route);
        }

        // Refresh the route name lookups
        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();
    }
}

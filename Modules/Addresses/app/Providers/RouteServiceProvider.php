<?php

namespace Modules\Addresses\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Addresses\Models\Address;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Addresses';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();

        Route::bind("myAddress", function ($value) {
            return Address::where("id", $value)
                ->where("user_id", user()?->id)
                ->firstOrFail();
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        // $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')->prefix('api')->name('api.')->group(module_path($this->name, '/routes/api.php'));
    }
}

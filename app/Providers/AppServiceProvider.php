<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Middleware\CheckRole;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('role', CheckRole::class);
        
        // Configuraciones para producción
        if (config('app.env') === 'production') {
            // Forzar HTTPS en producción
            URL::forceScheme('https');
            
            // Configurar proxies confiables para plataformas de hosting
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}

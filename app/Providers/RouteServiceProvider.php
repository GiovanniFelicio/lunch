<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();
        $this->mapCategoriasRoutes();
        $this->mapConsumidoresRoutes();
        $this->mapLocaisRoutes();
        $this->mapRelatoriosRoutes();
        $this->mapVendasRoutes();
        $this->mapCrtRoutes();
        $this->mapBairrosRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/admin.php'));
    }
    protected function mapCategoriasRoutes()
    {
        Route::prefix('categorias')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/categorias.php'));
    }

    protected function mapConsumidoresRoutes()
    {
        Route::prefix('consumidores')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/consumidores.php'));
    }
    protected function mapLocaisRoutes()
    {
        Route::prefix('locais')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/locais.php'));
    }
    protected function mapRelatoriosRoutes()
    {
        Route::prefix('relatorios')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/relatorios.php'));
    }

    protected function mapVendasRoutes()
    {
        Route::prefix('vendas')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/vendas.php'));
    }
    protected function mapCrtRoutes()
    {
        Route::prefix('crt')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/crt.php'));
    }
    protected function mapBairrosRoutes()
    {
        Route::prefix('bairros')
             ->middleware('web', 'auth', 'auth.unique.user')
             ->namespace($this->namespace)
             ->group(base_path('routes/my_routes/bairros.php'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRouteModuleMacro();
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'frontend'])
                ->namespace($this->namespace)
                ->group(base_path('routes/frontend.php'));

            Route::prefix('admin')
                ->middleware(['web', 'backend'])
                ->namespace($this->namespace)
                ->group(base_path('routes/backend.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(300)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * registerRouteModuleMacro
     *
     * @return void
     */
    private function registerRouteModuleMacro()
    {
        Router::macro('module', function ($controller, array $options = []) {
            $actions = ['index', 'form', 'store', 'destroy', 'restore'];

            if (isset($options['only'])) {
                $actions = array_intersect($actions, (array) $options['only']);
            }

            if (isset($options['except'])) {
                $actions = array_diff($actions, (array) $options['except']);
            }

            $methods = collect([
                'GET' => ['index', 'form'],
                'POST' => ['store', 'destroy', 'restore']
            ]);

            $resource = Str::plural(Str::kebab((str_replace('Controller', '', class_basename($controller)))));

            foreach ($actions as $action) {
                $method = $methods->search(fn ($item) => in_array($action, $item));
                Route::addRoute($method, "$resource/$action", "$controller@$action")
                    ->name("$resource.$action");
            }
        });
    }
}

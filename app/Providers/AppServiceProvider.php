<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if (config('app.env') === 'production') {
        //     \URL::forceScheme('https');
        // }

        $mainPath = database_path('migrations');
        $paths = array_merge([$mainPath], glob($mainPath . '/*', GLOB_ONLYDIR));

        $this->loadMigrationsFrom($paths);

        $this->registerWhereLikeMacro();

        Model::preventLazyLoading(!app()->isProduction());
    }


    private function registerWhereLikeMacro()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $terms = explode(' ', $searchTerm);
                            foreach ($terms as $term) {
                                $query->orWhere($attribute, 'LIKE', $term);
                            }
                            foreach ($terms as $term) {
                                $query->orWhere($attribute, 'LIKE', "{$term}%");
                            }
                            foreach ($terms as $term) {
                                $query->orWhere($attribute, 'LIKE', "%{$term}%");
                            }
                        }
                    );
                }
            });

            return $this;
        });
    }
}

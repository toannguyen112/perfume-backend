<?php

namespace Jamstackvietnam\Support\Controllers;

use Illuminate\Routing\Controller;

class HelperController extends Controller
{
    public function getLogs()
    {
        $file = request()->input('file');
        if ($file) {
            return response(file_get_contents(storage_path('logs/' . $file)))
                ->header('Content-Type', 'text/plain');
        } else {
            $files = glob(storage_path('logs/laravel-*.log'));
            $files = collect($files)->map(function ($file) {
                return [
                    'file' => basename($file),
                    'url' => route('helper.logs', ['file' => basename($file)])
                ];
            })->reverse()->take(10)->values();
            return response()->json($files);
        }
    }

    public function getModelData()
    {
        $model = request()->input('model');

        if ($const = request()->input('const')) {
            $model = $this->modelNamespace();
            $items = constant($model . '::' . $const);
        } else {
            $model = $this->modelNamespace();
            $model = new $model;
            $method = request()->input('method');

            if (request()->has('except_ids')) {
                $model = $model->whereNotIn('id', request()->input('except_ids'));
            }

            if ($params = request()->input('params')){
                $items = $model->$method($params);
            } else {
                $items = $model->$method();
            }

            $items = $this->onlyFields($items);
        };

        return $items;
    }

    // Private

    private function modelNamespace()
    {
        $model = request()->input('model');
        return "\App\Models\\$model";
    }

    private function onlyFields($items)
    {
        if (request()->has('only')) {
            return $items->map->only(request()->input('only'));
        } else if (request()->input('method') === 'get') {
            return $items->map->only(['id', request()->input('label', 'name')]);
        }

        return $items;
    }
}

<?php

namespace Jamstackvietnam\Support\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jamstackvietnam\Support\Traits\ApiResponse;

trait HasApiCrudActions
{
    use ApiResponse;
    private function filters($query)
    {
        if (!request()->has('filters')) return $query;
        return $query->filter();
    }

    private function orderBy($query)
    {
        return $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc');
    }

    private function addPagination($query)
    {
        return $query->paginate(request()->query('limit', 20));
    }

    public function store(Request $request)
    {
        $valid = $this->validateRequest(__FUNCTION__);

        if (!$valid) {
            return $valid;
        }

        $is_update = $request->has('id') && !is_null($request->input('id'));

        if ($is_update) {
            $resource = $this->model::findOrFail($request->input('id'));
            $resource->update($request->all());
        } else {
            $resource = $this->model::create($request->all());
        }

        return $resource;
    }

    private function validateRequest($action)
    {
        $rules = $this->getModelRules($action);
        $validator = Validator::make(request()->all(), $rules);

        return $validator->errors()->keys();
    }

    private function getModelRules($action)
    {
        $rules = (new $this->model)->modelRules();
        if (isset($rules[$action])) {
            return $rules[$action];
        }
        if (in_array($action, ['store', 'form'])) {
            return $rules['store'] ?? $rules['form'] ?? $rules['all'] ?? [];
        }
        return $rules['all'] ?? [];
    }
}

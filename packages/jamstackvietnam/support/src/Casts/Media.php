<?php

namespace Jamstackvietnam\Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Media implements CastsAttributes
{
    protected $multiple;

    public function __construct($relation = null, $multiple = true)
    {
        $this->relation = $relation;
        $this->multiple = $multiple;
    }

    public function get($model, $key, $value, $attributes)
    {
        return [
            'id' => $attributes[$key . '_id'] ?? 0,
            $key . '_url' => $attributes[$key . '_url'] ?? null,
        ];
    }

    public function set($model, $key, $value, $attributes)
    {
        return [
            $key . '_id' => $value ? $value['id'] : 0,
            $key . '_url' => $value ? $value['image_url'] : null
        ];
    }
}

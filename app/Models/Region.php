<?php

namespace App\Models;

use Jamstackvietnam\Support\Models\BaseModel;

class Region extends BaseModel
{
    public function scopeGetCities($query)
    {
        return $query
            ->where('parent_code', null)
            ->orderBy('sort', 'desc')
            ->orderBy('name_with_type')
            ->get()
            ->pluck('name_with_type', 'code')
            ->toArray();
    }

    public function scopeGetDistricts($query, $city_code)
    {
        return $query
            ->select('name_with_type', 'parent_code', 'code', 'id')
            ->where('parent_code', $city_code)
            ->orderBy('sort', 'desc')
            ->orderBy('name_with_type')
            ->get()
            ->toArray();
    }

    public function scopeCodeToId($query, $code)
    {
        try {
            return $query->where('code', $code)->first()->id;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function scopeCodeToName($query, $code)
    {
        try {
            return $query->where('code', $code)->first()->name_with_type;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

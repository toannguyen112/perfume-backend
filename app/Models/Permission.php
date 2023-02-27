<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public static function getPermissionList()
    {
        return self::get()->map(function (Permission $permission) {
            return array(
                'id' => $permission->id,
                'name' => trans('common.permission.' . explode('.', $permission->name)[1])
            );
        });
    }
}

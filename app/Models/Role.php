<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Arr;
use DB;

class Role extends SpatieRole
{
    protected $appends = ['role_permissions'];
    public $fillable = ['name', 'guard_name'];

    public function getRolePermissionsAttribute()
    {
        return DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $this->id)
            ->pluck('role_has_permissions.permission_id')
            ->all();
    }


    public function modelRules()
    {
        return [
            'all' => [],
        ];
    }

    protected static function booted()
    {
        static::saved(function ($coupon) {
            if (request()->route() === null) return;
            $coupon->saveRelations(request()->all());
        });
    }

    /**
     * Save associated relations for the coupon.
     *
     * @param array $attributes
     * @return void
     */
    public function saveRelations(array $attributes)
    {
        $rolePermissionIDs = Arr::get($attributes, 'role_permissions', []);

        if (!empty($rolePermissionIDs)) {
            $this->syncPermissions($rolePermissionIDs);
        }
    }
}

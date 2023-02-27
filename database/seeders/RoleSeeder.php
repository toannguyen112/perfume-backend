<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RoleSeeder
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'admin'],
            ['name' => 'Editor', 'guard_name' => 'admin']
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }
        $this->addAdmin();
        $this->addPermissions();
    }

    private function addAdmin()
    {
        Admin::query()->updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'status' => 1,
                'name' => 'admin',
                'password' => Hash::make('admin@gmail.com')
            ]
        );
    }

    private function addPermissions()
    {
        $permissions = [];
        foreach (Route::getRoutes() as $route) {
            $routeName = $route->getName();
            if (contains($routeName, 'sidebar') && contains($routeName, 'index')) {
                if (!in_array($routeName, $permissions)) {
                    $permissions[] = $routeName;
                }
            }
        }

        $role = Role::first();
        foreach ($permissions as $permission) {
            $new_permission = Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => $role->guard_name
            ]);
            $new_permission->assignRole($role);
        }

        Admin::first()->assignRole($role);
    }
}

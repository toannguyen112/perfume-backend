<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    use HasCrudActions;

    public $model = Role::class;

    protected $with = [
        'form' => [],
    ];

    public $appends = [
        'form' => ['role_permissions']
    ];
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use HasCrudActions;

    public $model = Admin::class;

    protected $with = [
        'form' => ['roles'],
    ];

    public $appends = [
        'index' => ['formatted_created_at', 'role_name'],
    ];
}

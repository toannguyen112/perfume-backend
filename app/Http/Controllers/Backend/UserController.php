<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Jamstackvietnam\Support\Traits\HasCrudActions;

class UserController extends Controller
{
    use HasCrudActions;

    public $model = User::class;
    protected $with = [
    ];
    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at', 'address_short'],
        'form' => []
    ];
}

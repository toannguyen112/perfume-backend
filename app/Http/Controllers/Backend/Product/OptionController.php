<?php

namespace App\Http\Controllers\Backend\Product;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use PhpOption\Option;

class OptionController extends Controller
{
    use HasCrudActions;

    public $model = Option::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
    ];
}

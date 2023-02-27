<?php

namespace App\Http\Controllers\Backend\Product;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;

class ProductController extends Controller
{
    use HasCrudActions;

    public $model = Product::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
    ];
}

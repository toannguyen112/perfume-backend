<?php

namespace App\Http\Controllers\Backend\Post;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use App\Models\Post\Post;

class PostController extends Controller
{
    use HasCrudActions;

    public $folder = 'Posts';

    public $model = Post::class;

    public $with = [
        'form' => ['categories']
    ];

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['thumbnail', 'post_related_ids']
    ];
}

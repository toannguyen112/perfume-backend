<?php

namespace App\Http\Controllers\Backend\Post;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use App\Models\Post\PostCategory;
use Inertia\Inertia;

class PostCategoryController extends Controller
{
    use HasCrudActions;

    public $model = PostCategory::class;

    public $folder = 'PostCategories';

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at']
    ];

    public function index()
    {
        $item = $this->initEmptyModel(new $this->model);

        return Inertia::render($this->folder() . '/Index', [
            'item' =>  $item,
            'rules' => $this->getModelRules('store')
        ]);
    }

    private function redirectToForm($id, $message)
    {
        $currentRoute =  request()->route()->getName();
        $currentRoutePaths = explode('.', $currentRoute);
        $currentRoutePaths[count($currentRoutePaths) - 1] = 'index';
        $formRoute = implode('.', $currentRoutePaths);

        return redirect(route($formRoute, ['id' => $id]))->with('success', $message);
    }
}

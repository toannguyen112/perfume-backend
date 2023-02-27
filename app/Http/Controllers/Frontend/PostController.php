<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use App\Models\Post;
use App\Models\PostCategory;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public $model = Post::class;

    public function index()
    {
        return $this->renderDataPage('Post/Index');
    }

    public function categoryShow($categorySlug)
    {
        return $this->renderDataPage('Post/Index', $categorySlug);
    }

    public function renderDataPage($path, $categorySlug = null)
    {
        $query = $this->model::query()
            ->active();

        $category = null;

        if (!empty($categorySlug)) {
            $category = PostCategory::query()
                ->active()
                ->whereSlug($categorySlug)
                ->firstOrFail();

            $category->increment('view');

            $category = $category->transform();

            $query = $query->whereHas('categories', function ($query) use ($category) {
                    $query->active()->where('post_categories.id', $category['id']);
                });
        }

        $posts = $query->orderByDesc('is_featured')
            ->orderByDesc('posted_at')
            ->orderByDesc('id')
            ->paginate(11)
            ->through(function ($item) {
                return $item->transform();
            })->withQueryString();

        $data = [
            'categories' => PostCategory::getCategories(),
            'category' => $category,
            'posts' => $posts,
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render($path, $data);
    }

    public function show($slug, $id)
    {
        $post = $this->model::query()
            ->active()
            ->findOrFail($id);

        $post->increment('view');

        $post = $post->transformDetails();

        $data = [
            'post' => $post,
            'related_posts' => $post['related_posts'],
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Post/Show', $data);
    }
}

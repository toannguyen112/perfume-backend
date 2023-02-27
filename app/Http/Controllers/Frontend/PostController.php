<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public $model = Post::class;

    public function index()
    {
        // $query = $this->model::query()
        //     ->active();

        // $posts = $query->orderByDesc('is_featured')
        //     ->orderByDesc('posted_at')
        //     ->orderByDesc('id')
        //     ->paginate(11)
        //     ->through(function ($item) {
        //         return $item->transform();
        //     })->withQueryString();

        // $data = ['posts' => $posts];

        return response()->json('Test1');
    }

    public function show($slug, $id)
    {
        $post = $this->model::query()
            ->active()->findOrFail($id);

        $post->increment('view');

        $post = $post->transformDetails();

        $data = [
            'post' => $post,
            'related_posts' => $post['related_posts'],
        ];

        return response()->json($data);
    }
}

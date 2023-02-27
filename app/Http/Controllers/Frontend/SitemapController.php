<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Jamstackvietnam\Sitemap\Sitemap;
use App\Models\Post;
use App\Models\PostCategory;

class SitemapController extends Controller
{
    public function index()
    {
        return Sitemap::create()
            ->addStaticRoutes()
            ->add(Post::active()->get())
            ->add(PostCategory::active()->get())
            ->render();
    }
}

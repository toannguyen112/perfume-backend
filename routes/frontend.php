<?php

use Illuminate\Support\Facades\Route;
use Jamstackvietnam\Support\Controllers\MediaController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\PostController;

Route::get('static/{staticId}/{year}/{month}/{day}/{filename}', [MediaController::class, 'cache']);
Route::get('sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', fn () => inertia('Home'));

Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index')->name('post.index');
    Route::get('/posts/{slug}', 'show')
        ->where('slug', '.*?')
        ->name('post.show');
});

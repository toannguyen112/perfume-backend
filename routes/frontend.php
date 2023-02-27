<?php

use Illuminate\Support\Facades\Route;
use Jamstackvietnam\Support\Controllers\MediaController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\PostController;

Route::get('static/{staticId}/{year}/{month}/{day}/{filename}', [MediaController::class, 'cache']);
Route::get('sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', fn() => inertia('Home'));
Route::get('/about', fn() => inertia('About'));

Route::controller(PostController::class)->group(function () {
    Route::get('/bai-viet', 'index')->name('post.index');
    Route::get('/bai-viet/{slug}', 'categoryShow')->name('post.category-show');
    Route::get('/{slug}-b-{id}', 'show')
        ->where('slug', '.*?')
        ->where('id', '\d+')
        ->name('post.show');
});

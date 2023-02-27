<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Post\PostController;
use App\Http\Controllers\Backend\Post\PostCategoryController;

use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\Backend\Product\OptionController;

use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\RoleController;

use Jamstackvietnam\Support\Controllers\MediaController;
use Jamstackvietnam\Support\Controllers\HelperController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::middleware(['auth:admin', 'permission'])->group(function () {
    Route::name('sidebar.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::module(PostController::class);

        Route::module(ProductController::class);
        Route::module(OptionController::class);

        Route::module(PostCategoryController::class);

        Route::module(UserController::class);

        Route::module(MediaController::class, ['only' => ['index', 'form', 'store', 'destroy']]);

        Route::module(AdminController::class);
        Route::module(RoleController::class);
    });

    Route::post('model-data', [HelperController::class, 'getModelData'])->name('helper.model-data');
    Route::get('logs', [HelperController::class, 'getLogs'])->name('helper.logs');
});

require __DIR__ . '/auth.php';

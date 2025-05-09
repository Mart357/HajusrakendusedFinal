<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route:: resource('posts', PostController::class)->middleware(['auth'])
    ->middleware(['auth', 'verified'])
    ->names([
        'index' => 'posts.index',
        'create' => 'posts.create',
        'store' => 'posts.store',
        'show' => 'posts.show',
        'edit' => 'posts.edit',
        'update' => 'posts.update',
        'destroy' => 'posts.destroy',
    ]);

    Route::post('/comment/{post}', [CommentController::class, 'store'])->name('comments.store');

 Route::resource('markers', MarkerController::class)
    ->middleware(['auth', 'verified'])
    ->names([
        'index' => 'markers.index',
        'create' => 'markers.create',
        'store' => 'markers.store',
        'show' => 'markers.show',
        'edit' => 'markers.edit',
        'update' => 'markers.update',
        'destroy' => 'markers.destroy',
    ]);

    Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');


    Route::controller(CartController::class)
    ->middleware('auth')
    ->prefix('/cart')
    ->name('cart.')
    ->group(function () {
        Route::post('/add/{product}', 'add')->name('add');
        Route::get('/', 'view')->name('checkout');
        Route::post('/clear', 'clear')->name('clear');
        Route::post('/update', 'update')->name('update');
    });


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

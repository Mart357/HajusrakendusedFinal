<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route:: resource('posts', PostController::class)
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

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

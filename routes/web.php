<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;


Route::resource('subjects', SubjectController::class)
    ->middleware(['auth', 'verified'])
    ->names([
        'index' => 'subjects.index',
        'create' => 'subjects.create',
        'store' => 'subjects.store',
        'show' => 'subjects.show',
        'edit' => 'subjects.edit',
        'update' => 'subjects.update',
        'destroy' => 'subjects.destroy',
    ]);


Route::get('/', function (Request $request) {
    $success = false;
    $message = null;

    if ($request->has('session_id')) {
        // Tühjenda ostukorv
        session()->forget('cart');
        $success = true;
        $message = 'Makse õnnestus! Aitäh ostu eest.';
    }

    return Inertia::render('Welcome', [
        'success' => $success,
        'message' => $message,
    ]);
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

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

 Route::resource('markers', MarkerController::class)
    ->middleware(['auth', 'verified']);


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
            Route::post('/remove', 'remove')->name('remove'); // <-- FIXED

            
        });
    

Route::post('/cart/checkout', function (Request $request) {
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Cart is empty!');
    }

    $lineItems = [];
    foreach ($cart as $id => $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => intval($item['price'] * 100),
            ],
            'quantity' => $item['quantity'],
        ];
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('products.index') . '?success=1',
        'cancel_url' => route('cart.checkout'),
    ]);

    return redirect($session->url);
})->middleware('auth')->name('cart.stripe');

Route::get('/cart/success', [CartController::class, 'stripeSuccess'])->name('cart.success');
Route::get('/cart/cancel', [CartController::class, 'stripeCancel'])->name('cart.cancel');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

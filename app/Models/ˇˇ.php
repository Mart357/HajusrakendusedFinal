<?php
public function index(Request $request)
{
    $success = false;
    $message = null;

    if ($request->has('success')) {
        session()->forget('cart');
        $success = true;
        $message = 'Makse õnnestus! Aitäh ostu eest.';
    }

    return Inertia::render('products/Index', [
        'products' => Product::all(),
        'success' => $success,
        'message' => $message,
    ]);
}ˇˇ
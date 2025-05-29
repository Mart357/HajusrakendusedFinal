<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller {

public function add(Request $request, Product $product)
{

        $cart = session()->get('cart', []);

        if (data_get($cart, $product->id)) {
            $cart[$product->id]['quantity'] += 1;
        } else {
            $cart[$product->id] =
            [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'description' => $product->description,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart');
}

public function remove(Request $request)
{
    $cart = session()->get('cart', []);
    $id = $request->id;

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Product removed from cart');
}   

public function clear()
{
    session()->forget('cart');
    return redirect()->to(route('products.index'));
}

public function view()
{
    return Inertia::render('Cart');
}

public function update(Request $request)
{
    $cart = session()-> get('cart');

    if(data_get($cart, $request->id)) {
        $cart[$request->id]['quantity'] = $request->quantity;
    }
    
    session()->put('cart', $cart);

    return redirect()->back();
}

public function stripeCheckout(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $cart = session()->get('cart', []);
    $lineItems = [];

    foreach ($cart as $id => $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => $item['price'] * 100,
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('products.index', [], true) . '?success=1',
        'cancel_url' => route('cart.checkout', [], true),
    ]);

    // Salvestame tellimuse "pending" staatusega
    $order = Order::create([
        'user_id' => auth()->id(),
        'items' => json_encode($cart),
        'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
        'stripe_session_id' => $session->id,
    ]);

    return redirect($session->url);
}

public function stripeSuccess(Request $request)
{
    $session_id = $request->get('session_id');
    if (!$session_id) {
        return redirect()->route('cart.checkout')->with('error', 'Payment failed!');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));
    $session = StripeSession::retrieve($session_id);

    // Leia tellimus
    $order = \App\Models\Order::where('stripe_session_id', $session_id)->first();
    if ($order) {
        // Märgi tellimus makstuks
        $order->update(['stripe_session_id' => null]);
    }

    // Tühjenda ostukorv
    session()->forget('cart');

    return redirect()->route('cart.checkout')->with('success', 'Payment successful! Thank you for your purchase.');
}

public function stripeCancel()
{
    return redirect()->route('cart.checkout')->with('error', 'Payment was cancelled or failed.');
}
}
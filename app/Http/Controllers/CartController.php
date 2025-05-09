<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

public function remove()
{
    // Add product to cart
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

}
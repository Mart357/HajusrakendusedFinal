<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
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
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
    public function remove() 
    {
        // Logic to remove an item from the cart
    }
    public function clear() 
    {

    }
    public function view() 
    {
        // Logic to view the cart
    }
    public function update() 
    {
        // Logic to update the cart
    }
}

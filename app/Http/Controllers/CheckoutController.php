<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CheckoutController extends Controller
{
    /**
     * Undocumented function
     */
    public function checkout()
    {
        $product = Product::first();
        $user = auth()->user();

        return view('checkout', compact('product', 'user'));
    }
}

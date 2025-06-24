<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cartIndex(Request $request)
    {
        $cart = $this->getCart($request);
        return response()->json($cart->load('items.product'));
    }

    private function getCart(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'session_id' => $request->session()->getId(),
                ]
            );
            return $cart;
        }
    }

    public function addToCart(Request $request)
    {
        $cart = $this->getCart($request);
        $cartId = $cart->id;
        $productId = $request->product_id;
        $product = Product::find($productId);
        $quantity = $request->quantity;
        $cartItem = CartItem::updateOrCreate([
            'product_id' => $productId,
            'cart_id' => $cartId,
        ], ['quantity' => $quantity]);
        return response()->json($cart->load('items.product'));
    }
}

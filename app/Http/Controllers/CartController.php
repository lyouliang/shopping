<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        return Inertia::render('cart/Index', [
            'cart' => $cart->load('items.product')
        ]);
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

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart($request);
        $cartId = $cart->id;
        $productId = $request->product_id;
        $product = Product::find($productId);
        $quantity = $request->quantity;
        $cartItem = CartItem::updateOrCreate([
            'product_id' => $productId,
            'cart_id' => $cartId,
        ], ['quantity' => $quantity]);
        return redirect()->route('cart.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = $this->getCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($id);

        $cartItem->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index');
    }

    public function remove(Request $request, $id)
    {
        $cart = $this->getCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($id);
        $cartItem->delete();
        return redirect()->route('cart.index');
    }
}

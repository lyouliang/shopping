<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $product = Product::find($request->product_id);
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        // Calculate total quantity (existing + new)
        $newQuantity = $existingItem ? $existingItem->quantity + $request->quantity : $request->quantity;

        $cartItem = CartItem::updateOrCreate([
            'product_id' => $product->id,
            'cart_id' => $cart->id,
        ], ['quantity' => $newQuantity]);

        return Inertia::render('shop/Index', [
            'products' => Product::all(),
            'cart' => $cart->fresh()->load('items.product'),
            'filters' => ['search' => $request->input('search', '')],
            'flash' => ['success' => 'Item added to cart'],
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart = $this->getCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($id);

        $cartItem->update(['quantity' => $request->quantity]);
        return Inertia::render('shop/Index', [
            'products' => Product::all(),
            'cart' => $cart->fresh()->load('items.product'),
            'flash' => ['success' => 'Quantity updates'],
        ]);
    }

    public function remove(Request $request, $id)
    {
        $cart = $this->getCart($request);
        $cartItem = CartItem::where('cart_id', $cart->id)->findOrFail($id);
        $cartItem->delete();
        return Inertia::render('shop/Index', [
            'products' => Product::all(),
            'cart' => $cart->fresh()->load('items.product'),
            'flash' => ['success' => 'Item removed'],
        ]);
    }
}

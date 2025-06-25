<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        $search = $request->input('search', '');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();

        return Inertia::render('shop/Index', [
            'products' => $products,
            'cart' => $cart->load('items.product'),
            'filters' => ['search' => $search],
        ]);
    }

    private function getCart(Request $request) 
    {
        $user = Auth::user();
        if($user) {
            return Cart::firstOrCreate(['user_id' => $user->id, 'session_id' => Session::getId()]);
        }
    }

    public function login(Request $request)
    {
        return Inertia::render('shop/Login');
    }

    public function register(Request $request)
    {
        return Inertia::render('shop/Register');
    }

    public function loginSuccess(Request $request)
    {
        return Inertia::render('shop/Index', [
            'login_success' => 'login is successful'
        ]);
    }
}

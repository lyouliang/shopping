<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        return Inertia::render('shop/Index', [
            'products' => $products
        ]);
    }

    public function login(Request $request)
    {
        return Inertia::render('shop/Login');
    }

    public function register(Request $request)
    {
        return Inertia::render('shop/Register');
    }

    public function loginSuccess(Request $request) {
        return Inertia::render('shop/Index', [
            'login_success' => 'login is successful'
        ]);
    }
}

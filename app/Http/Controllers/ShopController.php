<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('products')
            ->select(['id', 'name', 'price', 'image_path'])
            ->get()->toArray();
        $products = collect($products)->map(function ($product) {
            $product->image_path = Storage::url("images") . "/" . $product->image_path . ".png";
            return $product;
        });
        return Inertia::render('shop/Index', [
            'products' => $products,
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

    public function loginSuccess(Request $request)
    {
        return Inertia::render('shop/Index', [
            'login_success' => 'login is successful'
        ]);
    }
}

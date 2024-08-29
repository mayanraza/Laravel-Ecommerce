<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {

        $product = Product::where('is_featured', "Yes")
            ->orderBy("id", "Desc")
            ->where('status', 1)
            ->take(8)
            ->get();

        $latestProduct = Product::orderBy('id', "Desc")
            ->where('status', 1)
            ->take(8)
            ->get();

        return view('front.home', [
            "product" => $product,
            "latestProduct" => $latestProduct
        ]);
    }



    public function addToWishlist(Request $request)
    {

    }
}

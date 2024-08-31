<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\wishlist;
use Auth;
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

        $latestProduct = Product::orderBy('id', "ASC")
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
        // check user is login or not------
        if (Auth::check() == false) {
            session(['url.intended' => url()->previous()]);
            return response()->json([
                "status" => false,
            ]);
        }
        // {check user is login or not------


        $product = Product::find($request->id);



        // if product not available in database-------
        if ($product == null) {
            return response()->json([
                "status" => true,
                'message' => "Product not Found..!!"
            ]);
        }
        // if product not available in database-------




        wishlist::updateOrCreate(
            // 1st array works as where---
            [
                "user_id" => Auth::user()->id,
                "product_id" => $request->id
            ],

            [
                "user_id" => Auth::user()->id,
                "product_id" => $request->id
            ]

        );





        // $wishlist = new wishlist();

        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();

        return response()->json([
            "status" => true,
            'message' => "<strong>{$product->title}</strong> successfully added in wishlist"
        ]);

    }
}

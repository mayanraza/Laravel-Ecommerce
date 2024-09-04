<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Models\wishlist;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


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









    public function page($slug)
    {


        $page = Page::where("slug", $slug)->first();


        if ($page == null) {
            abort(404);
        }
        // dd($page);
        return view("front.page", ["page" => $page]);
    }










    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "subject" => "required",
            "message" => "required",
        ]);

        if ($validator->passes()) {

            // send email here------
            $emailData = [
                "name" => $request->name,
                "email" => $request->email,
                "subject" => $request->subject,
                "message" => $request->message,
                "mail_subject"=>"You have received a contact email"

            ];

            $adminEmail = User::where("role", "2")->first();

            Mail::to($adminEmail->email)->send(new ContactEmail($emailData));

            // send email here------

            // $pages = new Page();
            // $pages->name = $request->name;
            // $pages->slug = $request->slug;
            // $pages->content = $request->content;
            // $pages->save();

            session()->flash("success", "Mail sent successfully..!!");
            return response()->json([
                "status" => true,
                "message" => "Mail sent successfully..!!"
            ]);
        }




        return response()->json([
            "status" => false,
            "errors" => $validator->errors()
        ]);

    }

}

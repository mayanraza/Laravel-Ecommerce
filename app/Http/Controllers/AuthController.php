<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AuthController extends Controller
{



    public function register()
    {
        return view('front.account.register');

    }


    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',

        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->save();

            session()->flash("success", "You have been registered successfully");

            return response()->json([
                'status' => true,
                'message' => $validator->errors(),

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }








    }














    public function login(Request $request)
    {
        return view('front.account.login');
    }






    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));

                }
                return redirect()->route("account.profile");

            } else {
                return redirect()->route("account.login")
                    ->withInput($request->only('email'))
                    // session message shortcut 
                    ->with("error", "Either email/password is incorrect..!!");
            }

        } else {
            return redirect()->route("account.login")
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }






    public function profile()
    {
        return view("front.account.profile");
    }






    public function logout()
    {
        Auth::logout();
        return redirect()->route("account.login")
            ->with("success", "You successfully Logged out..!!");

    }








    public function Orders()
    {
        $user = Auth::user();
        $order = Order::where("user_id", $user->id)->orderBy("created_at", "desc")->get();
        return view("front.account.order", ["order" => $order]);
    }








    public function orderDetail($id)
    {
        $user = Auth::user();

        $order = Order::where("user_id", $user->id)->where("id", $id)->first();

        $orderItem = OrderItem::where("order_id", $order->id)->get();

        return view("front.account.orderdetail", [
            "order" => $order,
            "orderItem" => $orderItem,
        ]);
    }







    public function wishlist()
    {

        $wishlist = wishlist::where("user_id", Auth::user()->id)->with("product")->get();
        // dd($wishlist);
        return view("front.account.wishlist", ["wishlist" => $wishlist]);

    }









    
    public function removeProductFromWishlist(Request $request)
    {

        $wishlist = wishlist::where("user_id", Auth::user()->id)->where("product_id", $request->id)->first();



        if ($wishlist == null) {
            session()->flash("error", "Product not found..!!");

            return response()->json([
                "status" => false,
                'message' => 'Product not found in wishlist'
            ]);
        } else {
            $wishlist->delete();

            session()->flash("success", "Product removed from wishlist successfully!");
            return response()->json([
                "status" => true,
                'message' => 'Product successfully removed from wishlist'
            ]);
        }



    }

}

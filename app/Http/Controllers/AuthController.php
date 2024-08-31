<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
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
        $users = User::where("id", Auth::user()->id)->first();
        $country = Country::orderBy("name", "asc")->get();
        $customerAddress = CustomerAddress::where("user_id", Auth::user()->id)->first();
        // dd($customerAddress);
        return view("front.account.profile", [
            "users" => $users,
            "country" => $country,
            "customerAddress" => $customerAddress
        ]);

    }



    public function updateProfile(Request $request)
    {



        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . 'id',
            'phone' => 'required',
        ]);


        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash("success", 'Profile updated successfully..!!');

            return response()->json([
                'status' => true,
                'message' => "Profile updated successfully..!!"
            ]);


        } else {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }

    }





    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
            'apartment' => "required"
        ]);


        if ($validate->passes()) {
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->first_name,
                    'zip' => $request->zip,
                    'mobile' => $request->mobile,
                ]
            );

            session()->flash("success", 'Customer Address updated successfully..!!');

            return response()->json([
                'status' => true,
                'message' => "Customer Address updated successfully..!!"
            ]);


        } else {
            return response()->json([
                'status' => false,
                'error' => $validate->errors()
            ]);
        }
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

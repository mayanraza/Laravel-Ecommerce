<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;



class CartController extends Controller
{



    public function addToCart(Request $request)
    {
        $product = Product::with("product_images")->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => "Product not found"
            ]);
        }

        if (Cart::count() > 0) {


            $cartContent = Cart::content();
            $productAlreadyExist = false;

            //    check if this product already in the cart 
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            // if this product not in the cart 
            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ""]);
                $status = true;
                $message = '<strong>' . $product->title . '</strong>  added in cart successfully';

                session()->flash("success", $message);

            } else {
                $status = false;
                $message = $product->title . " already added in cart ";
            }


        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ""]);
            $status = true;
            $message = '<strong>' . $product->title . '</strong>  added in cart successfully';
            session()->flash("success", $message);

        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }







    public function updateCart(Request $request)
    {

        // check product qty available in stock ----------
        $cartProductInfo = Cart::get($request->rowId);
        $product = Product::find($cartProductInfo->id);

        if ($product->track_qty == "Yes") {
            if ($request->qty <= $product->qty) {
                Cart::update($request->rowId, $request->qty);
                $status = true;
                $message = "Cart updated successfully";
                session()->flash("success", $message);

            } else {
                $status = false;
                $message = 'Requested qty(' . $request->qty . ') not available in stock.';
                session()->flash("error", $message);

            }
        } else {
            Cart::update($request->rowId, $request->qty);
            $status = true;
            $message = "Cart updated successfully";
            session()->flash("success", $message);

        }
        // check product qty available in stock ----------




        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }






    public function cart()
    {
        $cart = Cart::content();

        // dd(Cart::content());
        return view("front.cart", [
            "cart" => $cart,
        ]);
    }





    public function deleteItem(Request $request)
    {
        $cartProductInfo = Cart::get($request->rowId);

        if ($cartProductInfo == null) {
            session()->flash('error', 'Product not found in cart');
            return response()->json([
                'status' => false,
                'message' => 'Product not found in cart'
            ]);
        }


        Cart::remove($request->rowId);

        session()->flash('success', 'Product removed from cart successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart successfully'
        ]);

    }







    public function checkout()
    {


        $discount = 0;





        // if cart is empty not eligible to Go checkout page----
        if (Cart::count() == 0) {
            return redirect()->route("front.cart");
        }
        // if not Logged in, not eligible to Go checkout page----
        // check loggedin or not
        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route("account.login");
        }

        session()->forget('url.intended');


        $countries = Country::orderBy("name", "asc")->get();
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();



        $subtotal = Cart::subtotal(2, '.', '');

        // apply discount here----------
        if (session()->has("code")) {
            $code = session()->get("code");

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subtotal;
            } else {
                $discount = $code->discount_amount;
            }
        }
        // apply discount here----------





        // calculating Shipping Here----------------
        // 1)find user country
        // 2)find user country's shipping chahrges
        // 3)find Cart total quantity 
        // 4) qty * country shipping amount 
        // 5) subtotal + total shipping charges 

        if ($customerAddress != "") {
            $userCountry = $customerAddress->country_id;
            $shippingCharges = Shipping::where("country_id", $userCountry)->first();

            $totalQty = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            $totalShippingCharges = $totalQty * $shippingCharges->amount;
            $grandTotal = ($subtotal - $discount) + $totalShippingCharges;
        } else {
            $totalShippingCharges = $subtotal - $discount;
            $grandTotal = $subtotal;
        }


        // calculating Shipping Here----------------


        // dd($customerAddress);

        return view("front.checkout", [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharges' => $totalShippingCharges,
            'grandTotal' => $grandTotal,
            'discount' => $discount


        ]);


    }











    public function processCheckout(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }



        // store and update data in userAddress table
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
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


        // store data in order table
        if ($request->payment_method == 'cod') {
            $order = new Order;

            $discountCodeId = "";
            $promoCode = "";


            $subtotal = Cart::subtotal(2, '.', '');
            $shipping = 0;
            // $grandTotal = $subtotal + $shipping;


            // apply discount here----------
            if (session()->has("code")) {
                $code = session()->get("code");

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subtotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }
            // apply discount here----------



            // calculate shipping---------
            $shipping = Shipping::where("country_id", $request->country)->first();
            // dd($shipping);

            // if country rate set in admin panel----
            if ($shipping !== null) {
                $shipping = $shipping->amount * (Cart::count());
                $subtotal = Cart::subtotal(2, '.', '');
                $grandTotal = ($subtotal - $discount) + $shipping;
                // dd($totalShippingCharges);
            } else {
                // if country rate not set in admin panel----
                $shipping = Shipping::where("country_id", "rest_of_world")->first();
                $shipping = $shipping->amount * (Cart::count());
                $subtotal = Cart::subtotal(2, '.', '');
                $grandTotal = ($subtotal - $discount) + $shipping;
                // dd($shipping);
            }
            // calculate shipping---------




            $order->subtotal = $subtotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->first_name;
            $order->zip = $request->zip;
            $order->mobile = $request->mobile;
            $order->notes = $request->notes;
            $order->save();


            // store data in orderItem table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;

                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }

            session()->flash("success", "You have successfully places your order..!!");

            // Make Cart empty---
            Cart::destroy();

            // remove coupon from session after checkout----
            session()->forget("code");


            return response()->json([
                'status' => true,
                'message' => "You have successfully places your order..!!",
                'orderId' => $order->id,
            ]);

        } else {

        }


    }













    public function thankyou($id)
    {
        return view("front.thankyou", ['id' => $id]);
    }











    public function getOrderSummary(Request $request)
    {
        $subtotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountSection = '';
        // $discount = 0;

        // apply discount here----------
        if (session()->has("code")) {
            $code = session()->get("code");

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subtotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountSection = '<div class=" mt-4" id="discount-section">
            <strong> ' . session()->get('code')->code . ' </strong>
            <a class="btn btn-sm btn-danger" id="remove_coupon"><i class="fa fa-times"></i></a>
           </div>';

        }
        // apply discount here----------



        // if user select any country----
        if ($request->country_id > 0) {
            $shipping = Shipping::where("country_id", $request->country_id)->first();
            // dd($shipping);


            // if country rate set in admin panel----
            if ($shipping !== null) {
                $totalShippingCharges = $shipping->amount * (Cart::count());
                $subtotal = Cart::subtotal(2, '.', '');
                $grandTotal = ($subtotal - $discount) + $totalShippingCharges;

                // dd($totalShippingCharges);

                return response()->json([
                    'status' => true,
                    'grandTotal' => $grandTotal,
                    'totalShippingCharges' => $totalShippingCharges,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                    'discountSection' => $discountSection
                ]);

            } else {
                // if country rate not set in admin panel----
                $shipping = Shipping::where("country_id", "rest_of_world")->first();
                $totalShippingCharges = $shipping->amount * (Cart::count());
                $subtotal = Cart::subtotal(2, '.', '');
                $grandTotal = ($subtotal - $discount) + $totalShippingCharges;

                // dd($shipping);

                return response()->json([
                    'status' => true,
                    'grandTotal' => $grandTotal,
                    'totalShippingCharges' => $totalShippingCharges,
                    'discount' => $discount,
                    'discountSection' => $discountSection

                ]);

            }

        } else {
            // if user not select any country----
            $grandTotal = Cart::subtotal(2, '.', '');
            // dd($grandTotal);
            return response()->json([
                'status' => true,
                'grandTotal' => $grandTotal,
                'totalShippingCharges' => number_format(0, 2),
                'discountSection' => $discountSection

            ]);
        }

    }




















    public function applyDiscount(Request $request)
    {
        $code = DiscountCoupon::where("code", $request->code)->first();
        // dd($code);
        // check if coupon code is in database or not---
        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid discount coupon'
            ]);
        }

        // check if coupon code is expires or not---
        $now = Carbon::now();
        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon'
                ]);
            }
        }

        if ($code->expires_at != "") {
            $expiresDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($expiresDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon'
                ]);
            }
        }
        // save code in session---
        session()->put("code", $code);

        return $this->getOrderSummary($request);
    }

















    public function removeCoupon(Request $request)
    {
        session()->forget("code");
        return $this->getOrderSummary($request);

    }




}

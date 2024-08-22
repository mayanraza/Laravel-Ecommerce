<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{

    public function index(Request $request)
    {

        // $countries = Country::get();

        // return view("admin.shipping.create", [
        //     'countries' => $countries,

        // ]);
    }



    public function create(Request $request)
    {

        $countries = Country::get();
        $shippingListing = Shipping::select('shipping_charges.*', 'countries.name')
            ->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        // dd($shippingListing);
        return view("admin.shipping.create", [
            'countries' => $countries,
            'shippingListing' => $shippingListing
        ]);
    }




    public function store(Request $request)
    {




        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);
        if ($validator->passes()) {

            // check country already selected or not---------
            $count = Shipping::where("country_id", $request->country)->count();
            // dd($count);
            if ($count > 0) {
                session()->flash("error", 'Country already added');
                return response()->json([
                    'status' => true,
                ]);
            }
            // check country already selected or not---------

            $shipping = new Shipping();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash("success", 'Shipping added successfully..!!');
            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }







    public function edit(Request $request, $id)
    {
        $countries = Country::get();

        $shipping = Shipping::find($id);
        // dd($shipping);
        return view("admin.shipping.edit", [
            "shipping" => $shipping,
            'countries' => $countries

        ]);

    }








    public function update(Request $request, $id)
    {
        $shipping = Shipping::find($id);
        // dd($shipping);

       
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);


        if ($validator->passes()) {

            if ($shipping == null) {
                $request->session()->flash("error", 'Shipping does not exist');
    
                return response()->json([
                    'status' => true,
                    'message' => 'Shipping does not exist'
                ]);
            }


            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();


        }
        session()->flash("success", "Shipping updated successfully..!!");
        return response()->json([

            'status' => true,
            'message' => "Shipping updated successfully..!!"
        ]);
    }





    public function destroy(Request $request, $id)
    {
        $shipping = Shipping::find($id);
        if ($shipping == null) {
            $request->session()->flash("error", 'Shipping does not exist');

            return response()->json([
                'status' => true,
                'message' => 'Shipping does not exist'
            ]);
        }

        // Delete brand
        $shipping->delete();

        // Flash success message
        $request->session()->flash("success", 'Shipping deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Shipping deleted successfully'
        ]);
    }





}




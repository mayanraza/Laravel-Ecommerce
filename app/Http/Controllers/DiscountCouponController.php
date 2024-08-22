<?php

namespace App\Http\Controllers;
use App\Models\DiscountCoupon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use PhpParser\Builder\TraitUseAdaptation;

class DiscountCouponController extends Controller
{

    public function index(Request $request)
    {
        $discount = DiscountCoupon::latest();
        if (!empty($request->get('list_search'))) {
            $discount = $discount->where('name', 'like', '%' . $request->get('list_search') . '%')
                ->orWhere('code', 'like', '%' . $request->get('list_search') . '%');
        }

        $discount = $discount->paginate(10);

        return view("admin.discount.list", ['discount' => $discount]);
    }





    public function create()
    {
        return view("admin.discount.create");
    }






    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',

        ]);

        if ($validator->passes()) {



            //starting date must be greater than current date
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'start date cannot be less than current date']
                    ]);
                }
            }
            //starting date must be greater than current date



            //expiry date must be greater than starting date
            if (!empty($request->expires_at) && !empty($request->starts_at)) {

                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                if ($expiresAt->gte($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'expires date must be greater than start date']
                    ]);
                }
            }
            //expiry date must be greater than starting date



            $discount = new DiscountCoupon();

            $discount->code = $request->code;
            $discount->name = $request->name;
            $discount->description = $request->description;
            $discount->max_uses = $request->max_uses;
            $discount->max_uses_user = $request->max_uses_user;
            $discount->type = $request->type;
            $discount->discount_amount = $request->discount_amount;
            $discount->min_amount = $request->min_amount;
            $discount->starts_at = $request->starts_at;
            $discount->status = $request->status;
            $discount->expires_at = $request->expires_at;

            $discount->save();

            session()->flash("success", 'Discount added successfully..!!');
            return response()->json([
                'status' => true,
                'message' => "Discount added successfully"
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

        $discount = DiscountCoupon::find($id);
        if ($discount == null) {
            session()->flash("error", "Record not found");
            return redirect()->route("discount.index");
        }

        return view("admin.discount.edit", ['discount' => $discount]);



    }






    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',

        ]);





        if ($validator->passes()) {

            //starting date not necessary in Edit model

            //expiry date must be greater than starting date
            if (!empty($request->expires_at) && !empty($request->starts_at)) {

                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                if ($expiresAt->gte($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'expires date must be greater than start date']
                    ]);
                }
            }
            //expiry date must be greater than starting date






            $discount = DiscountCoupon::find($id);

            if ($discount == null) {
                session()->flash("error", "Record not found");
                return response()->json([
                    'status' => true,
                    "message" => "Record not found"
                ]);
            }





            $discount->code = $request->code;
            $discount->name = $request->name;
            $discount->description = $request->description;
            $discount->max_uses = $request->max_uses;
            $discount->max_uses_user = $request->max_uses_user;
            $discount->type = $request->type;
            $discount->discount_amount = $request->discount_amount;
            $discount->min_amount = $request->min_amount;
            $discount->starts_at = $request->starts_at;
            $discount->status = $request->status;
            $discount->expires_at = $request->expires_at;

            $discount->save();

            session()->flash("success", 'Discount Updated successfully..!!');
            return response()->json([
                'status' => true,
                'message' => "Discount Updated successfully"
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }



    }








    public function destroy(Request $request, $id)
    {

        $discount = DiscountCoupon::find($id);

        if (empty($discount)) {
            session()->flash("error", "Record not found");
            return response()->json([
                'status' => false,
                "message" => "Discount not found"
            ]);
        }


        $discount->delete();
        session()->flash("success", "Discount deleted successfully..!! ");

        return response()->json([
            'status' => true,
            "message" => "Discount deleted successfully..!!"
        ]);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Hash;


class SettingController extends Controller
{
    public function showChangePassword()
    {
        return view("admin.changePassword");
    }








    public function ChangePassword(Request $request)
    {




        $validator = Validator::make($request->all(), [
            "old_password" => "required",
            "new_password" => "required|min:5",
            "confirm_password" => "required|same:new_password",

        ]);

        if ($validator->passes()) {


            $user = User::select("id", "password")->where("id", Auth::guard("admin")->user()->id)->first();
            // check if old password and password in databease is same or not 
            if (!Hash::check($request->old_password, $user->password)) {
                session()->flash("error", "Your old password is incorrect, please try again..!!");
                return response()->json([
                    "status" => true,
                ]);
            }
            // check if old password and password in databease is same or not 


            // update new password----
            User::where("id", $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            // update new password----


            session()->flash("success", "Password updated successfully..!!");

            return response()->json([
                "status" => true,
            ]);


        } else {
            return response()->json([
                "status" => false,
                "error" => $validator->errors()
            ]);
        }



    }


}

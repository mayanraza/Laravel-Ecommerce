<?php

namespace App\Http\Controllers;

use App\Models\User;
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

}

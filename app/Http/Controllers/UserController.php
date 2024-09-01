<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::latest();

        if (!empty($request->get("list_search"))) {
            $users = $users->where("name", "like", "%" . $request->get("list_search") . "%");
        }

        $users = $users->paginate(10);
        return view("admin.users.list", ["users" => $users]);
    }










    public function create()
    {
        return view("admin.users.create");
    }










    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users",
            "phone" => "required",
            "password" => "required|min:5",
        ]);

        if ($validator->passes()) {

            $users = new User();
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->status = $request->status;
            $users->password = Hash::make($request->password);

            $users->save();

            session()->flash("success", "User created successfully..!!");
            return response()->json([
                "status" => true,
                "message" => "User created successfully..!!"
            ]);
        }




        return response()->json([
            "status" => false,
            "errors" => $validator->errors()
        ]);


    }








    public function edit( $id)
    {
        $users = User::find($id);
        if ($users == null) {
            session()->flash("error", "User not Found..!!");
            return redirect()->route("users.index");

        }
        return view("admin.users.edit", ["users" => $users]);

    }











    public function update(Request $request, $id)
    {
        $users = User::find($id);

        if ($users == null) {
            session()->flash("error", "User not Found..!!");
            return redirect()->route("users.index");
        }



        $validator = Validator::make($request->all(), [
            "name" => "required",
            "phone" => "required",
            "email" => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {

            $users->name = $request->name;
            $users->phone = $request->phone;
            $users->status = $request->status;
            $users->email = $request->email;
            if ($request->password != "") {
                $users->password = Hash::make($request->password);
            }

            $users->save();

            session()->flash("success", "User created successfully..!!");
            return response()->json([
                "status" => true,
                "message" => "User created successfully..!!"
            ]);
        }




        return response()->json([
            "status" => false,
            "errors" => $validator->errors()
        ]);


    }













    public function destroy(Request $request, $id)
    {

        $users = User::find($id);

        if ($users == null) {
            session()->flash("error", "User not Found..!!");
            return redirect()->route("users.index");
        }


        $users->delete();


        $request->session()->flash("success", 'User deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard("admin")->user();
        // echo $admin->name, "<a href=" . route("admin.logout") . ">Logout</a>";
        return view("admin.dashboard");
    }

    public function logout()
    {
        Auth::guard("admin")->logout();
        return redirect()->route('admin.login');

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;

class DashboardController extends Controller
{
    public function index()
    {


        $totalOrders = Order::where("status", "!=", "cancelled")->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where("role", "1")->count();
        $totalRevenue = Order::where("status", "!=", "cancelled")->sum("grand_total");

        $startDateOfCurrentMonth = Carbon::now()->startOfMonth()->format("Y-m-d");
        $currentDate = Carbon::now()->format("Y-m-d");
        $startDateOfPrevoiusMonth = Carbon::now()->subMonth()->startOfMonth()->format("Y-m-d");
        $lastDateOfPrevoiusMonth = Carbon::now()->subMonth()->lastOfMonth()->format("Y-m-d");
        $last30days = Carbon::now()->subDays(30)->format("Y-m-d");
        $lastMonthName = Carbon::now()->subMonth()->format('F');

        // dd($startDateOfCurrentMonth,$currentDate,$startDateOfPrevoiusMonth,$lastDateOfPrevoiusMonth,$last30days);


        $currentMonthTotalRevenue = Order::where("status", "!=", "cancelled")
            ->whereDate("created_at", ">=", $startDateOfCurrentMonth)
            ->whereDate("created_at", "<=", $currentDate)
            ->sum("grand_total");

        $previousMonthTotalRevenue = Order::where("status", "!=", "cancelled")
            ->whereDate("created_at", ">=", $startDateOfPrevoiusMonth)
            ->whereDate("created_at", "<=", $lastDateOfPrevoiusMonth)
            ->sum("grand_total");

        $Last30DayTotalRevenue = Order::where("status", "!=", "cancelled")
            ->whereDate("created_at", ">=", $last30days)
            ->whereDate("created_at", "<=", $currentDate)
            ->sum("grand_total");

        // dd($Last30DayTotalRevenue);

        return view("admin.dashboard", [
            "totalOrders" => $totalOrders,
            "totalProducts" => $totalProducts,
            "totalCustomers" => $totalCustomers,
            "totalRevenue" => $totalRevenue,
            "currentMonthTotalRevenue" => $currentMonthTotalRevenue,
            "previousMonthTotalRevenue" => $previousMonthTotalRevenue,
            "Last30DayTotalRevenue" => $Last30DayTotalRevenue,
            "lastMonthName" => $lastMonthName,


        ]);
    }

    public function logout()
    {
        Auth::guard("admin")->logout();
        return redirect()->route('admin.login');

    }
}

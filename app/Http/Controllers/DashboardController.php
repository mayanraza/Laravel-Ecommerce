<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use File;
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





        // delete temp images here-----------

        // find previous days except today----
        $dayBeforeToday = Carbon::now()->subDays(1)->format("Y-m-d");

        $tempImages = TempImage::where("created_at", "<=", $dayBeforeToday)->get();

        foreach ($tempImages as $value) {
            $path = public_path("/temp/" . $value->name);
            $thumbPath = public_path("/temp/thumb/" . $value->name);

            // delete temp images------
            if (File::exists($path)) {
                File::delete($path);
            }
            // delete temp images------

            // delete thumb images------
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }
            // delete thumb images------

            // delete from database-----
            TempImage::where("id", $value->id)->delete();
        }

        // delete temp images here-----------




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

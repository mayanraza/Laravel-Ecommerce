<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Items;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $order = Order::latest()->select("orders.*", "users.name");
        $order = $order->leftJoin("users", "users.id", "orders.user_id");


        if ($request->get("list_search") != "") {
            $order = $order->where('users.name', 'like', '%' . $request->get("list_search") . '%')
                ->orWhere('orders.email', 'like', '%' . $request->get("list_search") . '%')
                ->orWhere('orders.id', 'like', '%' . $request->get("list_search") . '%');


        }

        $order = $order->paginate(10);
        // dd($order);
        return view("admin.orders.list", ["order" => $order]);
    }











    public function details(Request $request, $id)
    {
        $order = Order::with("country")->where("id", $id)->first();


        $orderItem = OrderItem::where("order_id", $id)->get();

        // dd($orderItem);
        return view("admin.orders.detail", [
            "order" => $order,
            "orderItem" => $orderItem
        ]);

    }













    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);

        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;

        $order->save();

        session()->flash("success", "Order status updated successfully..!!");

        return response()->json([
            'status' => true,
            'message' => "Order status updated successfully..!!",


        ]);

    }




}
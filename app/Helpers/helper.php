<?php
use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\ProductImage;

use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;



function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->with('sub_Category')
        ->orderBy("id", "Desc")
        ->where("status", 1)
        ->where("showHome", "Yes")
        ->get();
}









function getProductImage($productId)
{
    return ProductImage::where("product_id", $productId)->first();
}









function orderEmail($orderId, $userType = "customer")
{
    $order = Order::where("id", $orderId)->with("items")->first();



    if ($userType == 'customer') {
        $subject = "Thanks for your order..!!";
        $email = $order->email;
    } else {
        $subject = "You have recieved an order..!!";
        $email = env('ADMIN_EMAIL');
    }

    $emailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($emailData));

    // dd($order);

    // return view("email.order", ["order" => $order]);
}











function footerPages()
{

    $pages = Page::orderBy("name", "asc")->get();

    return $pages;


}

?>
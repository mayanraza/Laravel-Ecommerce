<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class wishlistController extends Controller
{
    public function index()
    {
        return view("front.account.wishlist");
    }
}

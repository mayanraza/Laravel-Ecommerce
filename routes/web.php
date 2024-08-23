<?php

// controller 
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
// controller 

use App\Http\Controllers\DiscountCouponController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TempImagesController;
use App\Models\DiscountCoupon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });




Route::group(["prefix" => "admin"], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get("/login", [AdminLoginController::class, "index"])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });





    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get("/dashboard", [DashboardController::class, "index"])->name('admin.dashboard');
        Route::get("/logout", [DashboardController::class, "logout"])->name('admin.logout');


        // category module-------------------------------
        Route::get("/categories", [CategoryController::class, "index"])->name('categories.index');
        Route::get("/categories/create", [CategoryController::class, "create"])->name('categories.create');
        Route::post("/categories", [CategoryController::class, "store"])->name('categories.store');
        Route::get("/categories/{category}/edit", [CategoryController::class, "edit"])->name('categories.edit');
        Route::put("/categories/{category}", [CategoryController::class, "update"])->name('categories.update');
        Route::delete("/categories/{category}", [CategoryController::class, "destroy"])->name('categories.delete');

        // image route----
        Route::post("/upload-temp-image", [TempImagesController::class, "create"])->name('temp-images.create');
        // image route----

        // slug route 
        Route::get("/getSlug", function (Request $request) {
            $slug = "";
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);

            }
            return response()->json([
                'status' => true,
                'slug' => $slug

            ]);
        })->name("getSlug");
        // slug route 
    });
    // category module-------------------------------




    // Subcategory module-------------------------------
    Route::get("/subcategories", [SubCategoryController::class, "index"])->name('subcategories.index');
    Route::get("/subcategories/create", [SubCategoryController::class, "create"])->name('subcategories.create');
    Route::post("/subcategories", [SubCategoryController::class, "store"])->name('subcategories.store');
    Route::get("/subcategories/{subcategory}/edit", [SubCategoryController::class, "edit"])->name('subcategories.edit');
    Route::put("/subcategories/{subcategory}", [SubCategoryController::class, "update"])->name('subcategories.update');
    Route::delete("/subcategories/{subcategory}", [SubCategoryController::class, "destroy"])->name('subcategories.delete');
    // Subcategory module-------------------------------




    // brands module-------------------------------
    Route::get("/brands", [BrandController::class, "index"])->name('brands.index');
    Route::get("/brands/create", [BrandController::class, "create"])->name('brands.create');
    Route::post("/brands", [BrandController::class, "store"])->name('brands.store');
    Route::get("/brands/{brand}/edit", [BrandController::class, "edit"])->name('brands.edit');
    Route::put("/brands/{brand}", [BrandController::class, "update"])->name('brands.update');
    Route::delete("/brands/{brand}", [BrandController::class, "destroy"])->name('brands.delete');
    // brands module-------------------------------




    // product module-------------------------------
    Route::get("/products", [ProductController::class, "index"])->name('products.index');
    Route::get("/products/create", [ProductController::class, "create"])->name('products.create');
    Route::post("/products", [ProductController::class, "store"])->name('products.store');
    Route::get("/products/{product}/edit", [ProductController::class, "edit"])->name('products.edit');
    Route::put("/products/{product}", [ProductController::class, "update"])->name('products.update');
    Route::delete("/products/{product}", [ProductController::class, "destroy"])->name('products.delete');

    //  product-subcategory--------
    Route::get("/product-subcategories", [ProductSubCategoryController::class, "index"])->name('products-subcategories.index');
    //  product-subcategory--------

    // related-products route----
    Route::get("/get-products", [ProductController::class, "getProducts"])->name('products.getProducts');
    // related-products route----

    // product image update and delete-------
    Route::post("/products-images/update", [ProductImageController::class, "update"])->name('products-images.update');
    Route::delete("/products-images", [ProductImageController::class, "destroy"])->name('products-images.delete');
    // product image update and delete-------

    // product module-------------------------------



    // Shipping Module--------------------
    Route::get("/shipping", [ShippingController::class, "index"])->name('shipping.index');
    Route::get("/shipping/create", [ShippingController::class, "create"])->name('shipping.create');
    Route::post("/shipping", [ShippingController::class, "store"])->name('shipping.store');
    Route::get("/shipping/{ship}/edit", [ShippingController::class, "edit"])->name('shipping.edit');
    Route::put("/shipping/{ship}", [ShippingController::class, "update"])->name('shipping.update');
    Route::delete("/shipping/{ship}", [ShippingController::class, "destroy"])->name('shipping.delete');
    // Shipping Module--------------------




    // discount Module--------------------
    Route::get("/discount", [DiscountCouponController::class, "index"])->name('discount.index');
    Route::get("/discount/create", [DiscountCouponController::class, "create"])->name('discount.create');
    Route::post("/discount", [DiscountCouponController::class, "store"])->name('discount.store');
    Route::get("/discount/{discount}/edit", [DiscountCouponController::class, "edit"])->name('discount.edit');
    Route::put("/discount/{discount}", [DiscountCouponController::class, "update"])->name('discount.update');
    Route::delete("/discount/{discount}", [DiscountCouponController::class, "destroy"])->name('discount.delete');
    // discount Module--------------------




});






// Front---------------
Route::get('/', [FrontController::class, "index"])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, "index"])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, "product"])->name('front.product');
Route::get('/cart', [CartController::class, "cart"])->name('front.cart');
// add-to-cart-----
Route::post('/add-to-cart', [CartController::class, "addToCart"])->name('front.addToCart');
// add-to-cart-----
// Update Cart-----
Route::post('/update-cart', [CartController::class, "updateCart"])->name('front.updateCart');
// Update Car-----
// delete Car-----
Route::delete('/delete-item', [CartController::class, "deleteItem"])->name('front.deleteItem.cart');
// delete Car-----
Route::get('/cart', [CartController::class, "cart"])->name('front.cart');
// checkout Cart-----
Route::get('/checkout', [CartController::class, "checkout"])->name('front.checkout');
Route::post('/process-checkout', [CartController::class, "processCheckout"])->name('front.processCheckout');
// checkout Cart-----
// thankyou page-------
Route::get('/thankyou/{orderId}', [CartController::class, "thankyou"])->name('front.thankyou');
// thankyou page-------
Route::post('/get-order-summmary', [CartController::class, "getOrderSummary"])->name('front.getOrderSummary');
// discount coupon-----
Route::post('/apply-discount', [CartController::class, "applyDiscount"])->name('front.apply-discount');
// discount coupon-----
// remove discount-----
Route::post('/remove-discount', [CartController::class, "removeCoupon"])->name('front.remove_coupon');
// remove discount-----








// user registration/login/logout/profile---------
Route::group(["prefix" => "account"], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AuthController::class, "register"])->name('account.register');
        Route::post('/process-register', [AuthController::class, "processRegister"])->name('account.processRegister');
        Route::get('/login', [AuthController::class, "login"])->name('account.login');
        Route::post('/process-login', [AuthController::class, "processLogin"])->name('account.processLogin');
    });

    // Authorized User--------------------
    Route::group(["middleware" => "auth"], function () {
        Route::get('/profile', [AuthController::class, "profile"])->name('account.profile');
        Route::get('/logout', [AuthController::class, "logout"])->name('account.logout');
    });
    // Authorized User--------------------

});
// user registration/login/logout/profile---------







// Front---------------









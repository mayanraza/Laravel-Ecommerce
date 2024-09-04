<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {

        $categorySelected = "";
        $subCategorySelected = "";
        $brandArray = [];



        $category = Category::orderBy("name", "asc")
            ->with("sub_Category")
            ->where("status", 1)
            ->get();

        $brand = Brand::orderBy("name", "asc")
            ->where("status", 1)
            ->get();

        $product = Product::where("status", 1);

        // apply category based Filter her-------------
        if (!empty($categorySlug)) {

            $categories = Category::where("slug", $categorySlug)->first();
            $product = $product->where("category_id", $categories->id);
            $categorySelected = $categories->id;
        }
        // apply category based Filter her-------------

        // apply Sub category based Filter her-------------
        if (!empty($subCategorySlug)) {
            $subcategories = SubCategory::where("slug", $subCategorySlug)->first();
            $product = $product->where("sub_category_id", $subcategories->id);
            $subCategorySelected = $subcategories->id;
        }
        // apply Sub category based Filter her-------------

        // apply brand Filter her-------------
        if (!empty($request->get("brand"))) {
            $brandArray = explode(',', $request->get("brand"));
            $product = $product->where("brand_id", $brandArray);
        }
        // apply brand Filter her-------------

        // apply price range Filter her-------------
        if ($request->get("price_max") != "" && $request->get("price_min") != "") {
            if ($request->get("price_max") == 1000) {
                $product = $product->whereBetween("price", [intval($request->get("price_min")), 1000000]);
            } else {
                $product = $product->whereBetween("price", [intval($request->get("price_min")), intval($request->get("price_max"))]);
            }
        }
        // apply price range Filter her-------------






        // home page search-------------
        if (!empty($request->search)) {
            $product = Product::where("title", "like", "%" . $request->search . "%");

        }

        // home page search-------------












        // apply price dropdown Filter her-------------
        if ($request->get("sort") != "") {
            if ($request->get("sort") == 'latest') {
                $product = $product->orderBy("id", "desc");
            } else if ($request->get("sort") == "price_asc") {
                $product = $product->orderBy("price", "asc");

            } else {
                $product = $product->orderBy("price", "desc");

            }
        } else {
            $product = $product->orderBy("id", "desc");
        }
        // apply price dropdown Filter her-------------





        $product = $product->orderBy("id", "desc")
            ->with("product_images")
            ->paginate(20);

        return view('front.shop', [
            "category" => $category,
            "brand" => $brand,
            "product" => $product,
            "categorySelected" => $categorySelected,
            "subCategorySelected" => $subCategorySelected,
            "brandArray" => $brandArray,
            "priceMax" => (intval($request->get("price_max")) == 0) ? 500 : $request->get("price_max"),
            "priceMin" => intval($request->get("price_min")),
            "priceSort" => $request->get("sort"),





        ]);
    }



    public function product($slug)
    {

        $product = Product::where("slug", $slug)
            ->withCount("product_ratings")
            ->withSum("product_ratings","rating")
            ->with("product_images","product_ratings")
            ->first();

        // dd($product);


        if ($product == null) {
            abort(404);
        }

        $relatedProduct = [];
        if ($product->related_products != "") {
            $productArray = explode(",", $product->related_products);
            $relatedProduct = Product::whereIn("id", $productArray)
                ->where("status", 1)
                ->with("product_images")
                ->get();
        }

        return view("front.product", [
            "product" => $product,
            "relatedProduct" => $relatedProduct,


        ]);

    }







    public function productRating(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            "username" => 'required',
            "email" => 'required|email',
            "rating" => 'required',
            "comment" => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        }


        $count = ProductRating::where("email", $request->email)->count();

        if ($count > 0) {
            session()->flash("error", "You already rated thid product..!!");

            return response()->json([
                "status" => true,
            ]);
        }

        $productRating = new ProductRating();
        $productRating->product_id = $id;
        $productRating->username = $request->username;
        $productRating->email = $request->email;
        $productRating->rating = $request->rating;
        $productRating->comment = $request->comment;
        $productRating->status = 0;

        $productRating->save();


        session()->flash("success", "Review submitted successfully..!!");

        return response()->json([
            "status" => true,
            "errors" => "Review submitted successfully..!!"
        ]);


    }





}

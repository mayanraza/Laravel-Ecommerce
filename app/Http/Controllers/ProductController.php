<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {


        $product = Product::orderBy("id","asc")->with("product_images");
        // dd($product);

        if (!empty($request->get('list_search'))) {
            $product = $product->where('title', 'like', '%' . $request->get('list_search') . '%');
        }

        $product = $product->paginate(10);
        return view("admin.product.list", ['product' => $product]);

    }


    public function create()
    {
        $category = Category::orderBy('name', 'ASC')->get();
        $brand = Brand::orderBy('name', 'ASC')->get();
        $subcategory = SubCategory::orderBy('name', 'ASC')->get();


        return view("admin.product.create", [
            'category' => $category,
            'brand' => $brand,
            'subcategory' => $subcategory
        ]);
    }



    public function store(Request $request)
    {
        $rules = [
            "title" => 'required',
            "slug" => 'required|unique:products',
            "price" => 'required|numeric',
            "sku" => 'required|unique:products',
            "track_qty" => 'required|in:Yes,No',
            "category" => 'required|numeric',
            "is_featured" => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules["qty"] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->barcode = $request->barcode;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');
            $product->shipping_returns = $request->shipping_returns;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            // image save----
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);


                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    // create image name---
                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();
                    // create image name---



                    // generate product thumbnail-----

                    // large image---
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destinationPath = public_path() . '/uploads/product/large/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destinationPath);
                    // large image---


                    // small image---
                    $destinationPath = public_path() . '/uploads/product/small/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destinationPath);
                    // small image---

                    // generate product thumbnail-----


                }
            }
            // image save----

            $request->session()->flash("success", 'Product added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }



    public function edit($categoryId, Request $request)
    {
        $product = Product::find($categoryId);

        if (empty($product)) {
            // both for session message 
            // with("error","Product not found") 0r 
            // $request->session().flash('error',"Product not found");
            return redirect()->route('products.index')->with("error", "Product not found");
        }
        // fetch product images

        $productimage = ProductImage::where("product_id", $product->id)->get();






        $category = Category::orderBy('name', 'ASC')->get();
        $brand = Brand::orderBy('name', 'ASC')->get();
        $subcategory = SubCategory::where("category_id", $product->category_id)->get();

        // fetch relate_product----

        $related_product = [];
        if ($product->related_products !== "") {
            $productArray = explode(",", $product->related_products);
            $related_product = Product::whereIn("id", $productArray)->get();
        }
        // fetch relate_product----

        return view(
            'admin.product.edit',
            [
                'product' => $product,
                'category' => $category,
                'brand' => $brand,
                'subcategory' => $subcategory,
                'productimage' => $productimage,
                "related_product" => $related_product,
            ]
        );
    }


    public function update($categoryId, Request $request)
    {
        $product = Product::find($categoryId);

        $rules = [
            "title" => 'required',
            "slug" => 'required|unique:products,slug,' . $product->id . ',id',
            "price" => 'required|numeric',
            "sku" => 'required|unique:products,sku,' . $product->id . ',id',
            "track_qty" => 'required|in:Yes,No',
            "category" => 'required|numeric',
            "is_featured" => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules["qty"] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->barcode = $request->barcode;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            // image save----

            // image save----

            $request->session()->flash("success", 'Product updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy($categoryId, Request $request)
    {
        $product = Product::find($categoryId);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect() - route('categories.index');
        }

        // delete  image --------
        $productImage = ProductImage::where("product_id", $categoryId)->get();
        if (!empty($productImage)) {

            // to delete more than 1 image of the product so we sue loop
            foreach ($productImage as $item) {
                File::delete(public_path() . '/uploads/product/large/' . $item->image);
                File::delete(public_path() . '/uploads/product/small/' . $item->image);
            }
            ProductImage::where("product_id", $categoryId)->delete();

        }
        // delete  image --------


        // Delete category
        $product->delete();

        // Flash success message
        $request->session()->flash('success', 'Product deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);

    }


    public function getProducts(Request $request)
    {

        $tempProduct = [];
        if ($request->term != "") {
            $product = Product::where('title', 'like', '%' . $request->term . '%')->get();

            if ($product != null) {
                foreach ($product as $value) {
                    $tempProduct[] = array("id" => $value->id, "text" => $value->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true,

        ]);

    }
}

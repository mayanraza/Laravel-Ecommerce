<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {


        $brand = Brand::latest('id');
            

        // search box-----------
        if (!empty($request->get('list_search'))) {
            $brand = $brand->where('brands.name', 'like', '%' . $request->get('list_search') . '%');
        }
        // search box-----------


        $brand = $brand->paginate(10);
        // // dd($categories);
        return view("admin.brands.list", ["brand" => $brand]);


    }


    public function create()
    {
        return view('admin.brands.create');
    }


    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'required|unique:brands',
            "status" => "required",
        ]);

        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;

            $brand->save();

            $request->session()->flash("success", 'Brand created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand created successfully'
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
        $brand = Brand::find($categoryId);

        if (empty($brand)) {
            $request->session()->flash("error", "Record not found");
            return redirect()->route('brands.index');
        }


        

        return view("admin.brands.edit", ['brand' => $brand]);
    }


    public function update($categoryId, Request $request)
    {
        $brand = Brand::find($categoryId);


        if (empty($brand)) {
            $request->session()->flash("error", "Record not found");
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('subcategories.index');
        }

        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'required|unique:brands,slug,' . $brand->id . ',id',
            "status" => "required",
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;

            $brand->save();

            $request->session()->flash("success", 'Brand updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
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
        $brand = Brand::find($categoryId);


        if (empty($brand)) {
            $request->session()->flash("error", 'Brand not found');
            return response()->json([
                'status' => true,
                'notFound' => true
            ]);
        }

        // Delete brand
        $brand->delete();

        // Flash success message
        $request->session()->flash("success", 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);

    }
}

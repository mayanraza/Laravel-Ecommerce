<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {


        $subcategories = SubCategory::select('sub_categories.*', 'categories.name as categoriesName')
            ->latest('id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        // search box-----------
        if (!empty($request->get('list_search'))) {
            $subcategories = $subcategories->where('sub_categories.name', 'like', '%' . $request->get('list_search') . '%');
        }
        // search box-----------


        $subcategories = $subcategories->paginate(10);
        // // dd($categories);
        return view("admin.subcategory.list", ["subcategories" => $subcategories]);


    }


    public function create()
    {

        $categories = Category::orderBy('name', 'ASC')->get();

        return view("admin.subcategory.create", ['categories' => $categories]);
    }


    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'required|unique:sub_categories',
            "category" => "required",
            "status" => "required",
        ]);

        if ($validator->passes()) {
            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->showHome = $request->showHome;
            $subcategory->status = $request->status;

            $subcategory->category_id = $request->category;

            $subcategory->save();

            $request->session()->flash("success", 'Sub-Category created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category created successfully'
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
        $subcategory = SubCategory::find($categoryId);

        if (empty($subcategory)) {
            $request->session()->flash("error", "Record not found");
            return redirect()->route('subcategories.index');
        }


        $categories = Category::orderBy('name', 'ASC')->get();

        return view("admin.subcategory.edit", ['categories' => $categories, 'subcategory' => $subcategory]);
    }


    public function update($categoryId, Request $request)
    {
        $subcategory = SubCategory::find($categoryId);

        if (empty($subcategory)) {
            $request->session()->flash("error", "Record not found");
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('subcategories.index');
        }

        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'required|unique:sub_categories,slug,' . $subcategory->id . ',id',
            "category" => "required",
            "status" => "required",
        ]);

        if ($validator->passes()) {
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->showHome = $request->showHome;
            $subcategory->category_id = $request->category;

            $subcategory->save();

            $request->session()->flash("success", 'Sub-Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category updated successfully'
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
        $subcategory = SubCategory::find($categoryId);

        if (empty($subcategory)) {
            $request->session()->flash("error", 'Sub-Category not found');
            return response()->json([
                'status' => true,
                'notFound' => true
            ]);
        }

        // Delete category
        $subcategory->delete();

        // Flash success message
        $request->session()->flash("success", 'Sub-Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Sub-Category deleted successfully'
        ]);

    }
}

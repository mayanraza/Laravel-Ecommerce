<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class CategoryController extends Controller
{
    public function index(Request $request)
    {


        $categories = Category::latest();

        if (!empty($request->get('list_search'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('list_search') . '%');
        }

        $categories = $categories->paginate(10);
        // dd($categories);
        return view("admin.category.list", ['categories' => $categories]);

    }


    public function create()
    {
        return view("admin.category.create");
    }


    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'unique:categories',

        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();



            // save image here 
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $extension = last($extArray);
                $newImageName = $category->id . '.' . $extension;

                $sourcePath = public_path() . '/temp/' . $tempImage->name;
                $destinationPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sourcePath, $destinationPath);


                // generate image thumbnail---------
                $destinationPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                // Create an image instance from a file
                $img = Image::make($sourcePath);
                // height width 
                $img->fit(450, 600);
                // add callback functionality to retain maximal original image size
                $img->fit(800, 600, function ($constraint) {
                    $constraint->upsize();
                });
                // Save the image to a file
                $img->save($destinationPath);

                // generate image thumbnail---------



                $category->image = $newImageName;
                $category->save();

            }

            $request->session()->flash("success", 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
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
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', ['category' => $category]);
    }


    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);


        if (empty($category)) {
            $request->session()->flash("error", "Category not found");
            return response()->json([
                'status' => false,
                'message' => 'Category not Found',
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            "name" => 'required',
            "slug" => 'required|unique:categories,slug,' . $category->id . ',id',

        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;



            // save image here 
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $extension = last($extArray);
                $newImageName = $category->id . '-' . time() . '.' . $extension;

                $sourcePath = public_path() . '/temp/' . $tempImage->name;
                $destinationPath = public_path() . '/uploads/category/' . $newImageName;

                File::copy($sourcePath, $destinationPath);


                // generate image thumbnail---------
                $destinationPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                // Create an image instance from a file
                $img = Image::make($sourcePath);
                // height width 
                $img->fit(450, 600);
                // add callback functionality to retain maximal original image size
                $img->fit(800, 600, function ($constraint) {
                    $constraint->upsize();
                });
                // Save the image to a file
                $img->save($destinationPath);
                // generate image thumbnail---------

                $category->image = $newImageName;
                $category->save();

                // delete old image --------
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);
                // delete old image --------
            }

            $request->session()->flash("success", 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
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
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash("error", 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
            // return redirect() - route('categories.index');
        }

        // delete  image --------
        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);
        // delete  image --------


        // Delete category
        $category->delete();

        // Flash success message
        $request->session()->flash("success", 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);

    }
}

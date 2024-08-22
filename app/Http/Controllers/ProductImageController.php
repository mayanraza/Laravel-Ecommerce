<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {

        $image = $request->image;
        $extension = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();


        // insert in database-----
        $productimage = new ProductImage();
        $productimage->product_id = $request->product_id;
        $productimage->image = "NULL";
        $productimage->save();
        // insert in database-----


        // for update in database-----
        // create image name---
        $imageName = $request->product_id . '-' . $productimage->id . '-' . time() . '.' . $extension;
        $productimage->image = $imageName;
        $productimage->save();
        // create image name---
        // for update in database-----



        // generate product thumbnail-----

        // large image---
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


        return response()->json([
            'status' => true,
            'image_id' => $productimage->id,
            'ImagePath' => asset('uploads/product/small/' . $productimage->image),
            'message' => 'Image saved successfully'


        ]);


    }



    public function destroy(Request $request)
    {
        $productimage = ProductImage::find($request->id);


        if (empty($productimage)) {
            return response()->json([
                'status' => false,
                'message' => 'Image nor found',
            ]);
        }



        // delete images from folder

        // delete large image---
        File::delete(public_path('uploads/product/large/' . $productimage->image));
        // delete large image---

        // delete small image---
        File::delete(public_path('uploads/product/small/' . $productimage->image));
        // delete small image---

        $productimage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
        // delete images from folder

    }


}

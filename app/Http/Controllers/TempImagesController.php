<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Image;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {


        $image = $request->image;
        if (!empty($image)) {
            $extension = $image->getClientOriginalExtension();
            $newName = time() . '.' . $extension;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();


            $image->move(public_path() . '/temp', $newName);



            // generate thumbnail--------
            $sourcePath=public_path().'/temp/'.$newName;
            $destinationPath=public_path().'/temp/thumb/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destinationPath);
            // generate thumbnail--------



            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/'.$newName),
                'message' => 'image uploaded successfully'
            ]);

        }


        // dd($categories);

    }
}

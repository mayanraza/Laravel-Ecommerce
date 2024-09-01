<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();

        if (!empty($request->get("list_search"))) {
            $pages = $pages->where("name", "like", "%" . $request->get("list_search") . "%");
        }

        $pages = $pages->paginate(10);
        return view("admin.pages.list", ["pages" => $pages]);
    }

    public function create(Request $request)
    {
        return view("admin.pages.create");

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required",

        ]);

        if ($validator->passes()) {

            $pages = new Page();
            $pages->name = $request->name;
            $pages->slug = $request->slug;
            $pages->content = $request->content;
            $pages->save();

            session()->flash("success", "Page created successfully..!!");
            return response()->json([
                "status" => true,
                "message" => "Page created successfully..!!"
            ]);
        }




        return response()->json([
            "status" => false,
            "errors" => $validator->errors()
        ]);


    }














    public function edit($id)
    {
        $pages = Page::find($id);
        if ($pages == null) {
            session()->flash("error", "Page not Found..!!");
            return redirect()->route("pages.index");

        }
        return view("admin.pages.edit", ["pages" => $pages]);
    }












    public function update(Request $request, $id)
    {
        $pages = Page::find($id);

        if ($pages == null) {
            session()->flash("error", "Page not Found..!!");
            return redirect()->route("pages.index");
        }



        $validator = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required",
        ]);

        if ($validator->passes()) {

            $pages->name = $request->name;
            $pages->slug = $request->slug;
            $pages->content = $request->content;
            $pages->save();

            session()->flash("success", "Page Update successfully..!!");
            return response()->json([
                "status" => true,
                "message" => "Page Update successfully..!!"
            ]);
        }




        return response()->json([
            "status" => false,
            "errors" => $validator->errors()
        ]);
    }










    public function destroy(Request $request, $id)
    {
        $pages = Page::find($id);

        if ($pages == null) {
            session()->flash("error", "Page not Found..!!");
            return redirect()->route("pages.index");
        }


        $pages->delete();


        $request->session()->flash("success", 'Page deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Page deleted successfully'
        ]);
    }


}

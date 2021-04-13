<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoCategory;

class InfoCategoryController extends Controller
{
    public function index()
    {
        $categories = InfoCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "type" => "required",
            "name" => "required|unique:info_categories,name",
            "image" => "nullable|image"
        ]);
           
        $category = new InfoCategory; 

        if ($request->hasFile("image")) {
            $image_path = $request->file("image")->store("info-categories", "s3");
            $category->image = Storage::disk("s3")->url($image_path);
        }

        $category->type = $request->input("type");
        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Info category added"]);
    }

    public function edit($id)
    {
        $category = InfoCategory::findOrFail($id);
        return response()->json(["success" => true, "data" => $category]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|unique:info_categories,name," . $id,
            "image" => "nullable|image"
        ]);
        
        $category = InfoCategory::findOrFail($id);

        if ($request->hasFile("image")) {
            deleteImageForSingle($category->image);
            
            $image_path = $request->file("image")->store("info-categories", "s3");
            $category->image = Storage::disk("s3")->url($image_path);
        }

        $category->name = $request->name;
        $category->save();

        return redirect()->route("info-categories.index")->with("success", "Info category updated successfully");
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $category = InfoCategory::findOrFail($id);

            deleteImageForSingle($category->logo);
            $category->infos()->detach();
            
            $category->delete();

            Session::flash("success", "Info category deleted successfully");
            return response()->json(["success" => true]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfoCategory;

use Storage;

class InfoCategoryController extends Controller
{
    public function index()
    {
        $categories = InfoCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $rules = [
            "type" => "required",
            "name" => "required|unique:info_categories,name"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
           
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
        $rules = [
            "type" => "required",
            "name" => "required|unique:info_categories,name," . $id
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $category = InfoCategory::findOrFail($id);

        if ($request->hasFile("image")) {
            deleteImageForSingle($category->image);
            
            $image_path = $request->file("image")->store("info-categories", "s3");
            $category->image = Storage::disk("s3")->url($image_path);
        }

        $category->type = $request->input("type");
        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Info category updated"]);
    }

    public function destroy($id)
    {
        $category = InfoCategory::findOrFail($id);
        deleteImageForSingle($category->image);
        // $category->infos()->detach();
        $category->delete();
        return response()->json(["success" => true, "message" => "Info category deleted"]);
    }
}
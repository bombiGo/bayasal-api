<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\InfoCategory;

use Storage;

class InfoController extends Controller
{
    public function index()
    {
        $infos = Info::with("categories")->get();
        return response()->json($infos);
    }

    public function store(Request $request)
    {
        $rules = [
            "title" => "required|unique:infos,title|max:255",
            "subtitle" => "required|max:1000",
            "is_featured" => "required|boolean",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
           
        $info = new Info; 

        if ($request->hasFile("image")) {
            $image_path = $request->file("image")->store("infos", "s3");
            $info->image = Storage::disk("s3")->url($image_path);
        }

        if (!empty($request->input("content"))) {
            $data = convertBase64ToImageSrc($request->input("content"), "infos");
            $content = $data->saveHTML();
        } else {
            $content = "";
        }

        $info->title = $request->input("title");
        $info->subtitle = $request->input("subtitle");
        $info->is_featured = $request->input("is_featured");
        $info->content = $content;
        $info->save();

        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
            $info->categories()->attach($categories_id);
        }

        return response()->json(["success" => true, "message" => "Info added"]);
    }

    public function edit($id)
    {
        $info = Info::findOrFail($id);
        $info["categories"] = $info->categories;
        $categories = InfoCategory::all();
        return response()->json(["success" => true, "info" => $info, "categories" => $categories]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "title" => "required|max:255|unique:infos,title," . $id,
            "subtitle" => "required|max:1000",
            "is_featured" => "required|boolean",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $info = Info::findOrFail($id);

        if ($request->hasFile("image")) {
            deleteImageForSingle($info->image);
            
            $image_path = $request->file("image")->store("infos", "s3");
            $info->image = Storage::disk("s3")->url($image_path);
        }

        $info->title = $request->input("title");
        $info->subtitle = $request->input("subtitle");
        $info->is_featured = $request->input("is_featured");
        $info->content = $request->input("content");
        $info->save();

        $categories_id = [];
        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
        } 

        $info->categories()->sync($categories_id);

        return response()->json(["success" => true, "message" => "Info updated"]);
    }

    public function destroy($id)
    {
        $info = Info::findOrFail($id);
        deleteImageForSingle($info->image);
        $info->categories()->detach();
        $info->delete();
        
        return response()->json(["success" => true, "message" => "Info deleted"]);
    }
}
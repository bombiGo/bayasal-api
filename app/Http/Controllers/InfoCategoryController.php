<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
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
            $category->image = $this->get_upload_img_url("normal", $request->file("image"));
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
            delete_one_image($category->image);
            $category->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        $category->type = $request->input("type");
        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Info category updated"]);
    }

    public function destroy($id)
    {
        $category = InfoCategory::findOrFail($id);
        delete_one_image($category->image);
        $category->infos()->detach();
        $category->delete();
        return response()->json(["success" => true, "message" => "Info category deleted"]);
    }

    public function get_upload_img_url($thumb_type, $file_content)
    {
        $img = Image::make($file_content);
        $img_name = md5(microtime(true)) . "." . $file_content->extension();

        if ($thumb_type == "small") {
            $img_small = $img->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_small = $img_small->stream();
            $img_path = "infos/thumbs/small_" . $img_name;

            Storage::disk("s3")->put($img_path, $img_small->__toString());
        } else {
            $img_normal = $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_normal = $img_normal->stream();
            $img_path = "infos/" . $img_name;

            Storage::disk("s3")->put($img_path, $img_normal->__toString());
        }
        
        $url = Storage::disk("s3")->url($img_path);
        return $url;
    }
}
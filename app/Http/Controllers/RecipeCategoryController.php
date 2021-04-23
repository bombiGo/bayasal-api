<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;

use Storage;

class RecipeCategoryController extends Controller
{
    public function index()
    {
        $categories = RecipeCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $rules = [
            "name" => "required|unique:recipe_categories,name"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:512";
        }

        $this->validate($request, $rules);
           
        $category = new RecipeCategory; 

        if ($request->hasFile("image")) {
            $category->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Recipe category added"]);
    }

    public function edit($id)
    {
        $category = RecipeCategory::findOrFail($id);
        return response()->json(["success" => true, "data" => $category]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "required|unique:recipe_categories,name," . $id
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $category = RecipeCategory::findOrFail($id);

        if ($request->hasFile("image")) {
            delete_one_image($category->image);
            $category->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Recipe category updated"]);
    }

    public function destroy($id)
    {
        $category = RecipeCategory::findOrFail($id);
        delete_one_image($category->image);
        $category->recipes()->detach();
        $category->delete();
        return response()->json(["success" => true, "message" => "Recipe category deleted"]);
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
            $img_path = "recipes/thumbs/small_" . $img_name;

            Storage::disk("s3")->put($img_path, $img_small->__toString());
        } else {
            $img_normal = $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_normal = $img_normal->stream();
            $img_path = "recipes/" . $img_name;

            Storage::disk("s3")->put($img_path, $img_normal->__toString());
        }
        
        $url = Storage::disk("s3")->url($img_path);
        return $url;
    }
}
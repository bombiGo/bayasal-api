<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\RecipeCategory;

use Storage;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with("categories")->get();
        return response()->json($recipes);
    }

    public function store(Request $request)
    {
        $rules = [
            "name" => "required|unique:recipes,name|max:255",
            "subtitle" => "required|max:1000",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
           
        $recipe = new Recipe; 

        if ($request->hasFile("image")) {
            $recipe->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "recipes");
            $content = $data->saveHTML();
        } else {
            $content = "";
        }

        $recipe->name = $request->input("name");
        $recipe->subtitle = $request->input("subtitle");
        $recipe->content = $content;
        $recipe->save();

        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
            $recipe->categories()->attach($categories_id);
        }

        if ($request->hasFile("image")) {
            $recipe->thumbnail()->create([
                "small" => $this->get_upload_img_url("small", $request->file("image"))
            ]);
        }

        return response()->json(["success" => true, "message" => "Recipe added"]);
    }

    public function edit($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe["categories"] = $recipe->categories;
        $categories = RecipeCategory::all();
        return response()->json(["success" => true, "recipe" => $recipe, "categories" => $categories]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => "required|max:255|unique:recipes,name," . $id,
            "subtitle" => "required|max:1000",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $recipe = Recipe::findOrFail($id);

        if ($request->hasFile("image")) {
            delete_one_image($recipe->image);
            $recipe->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "recipes/editor");
            $content = $data->saveHTML();

            $old_content = $recipe->content;
            if (!empty($old_content)) {
                delete_all_image_old_editor($old_content, $content);    
            }
        } else {
            $content = "";
        }

        $recipe->name = $request->input("name");
        $recipe->subtitle = $request->input("subtitle");
        $recipe->content = $content;
        $recipe->save();

        $categories_id = [];
        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
        } 

        $recipe->categories()->sync($categories_id);

        if ($request->hasFile("image")) {
            if (!empty($recipe->thumbnail->small)) {
                delete_one_image($recipe->thumbnail->small);
            }

            if ($recipe->thumbnail()->count() == 0) {
                $recipe->thumbnail()->create([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            } else {
                $recipe->thumbnail()->update([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            }
        }

        return response()->json(["success" => true, "message" => "Recipe updated"]);
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        delete_all_image_editor($recipe->content);
        delete_one_image($recipe->image);
        
        if (!empty($recipe->thumbnail->small)) {
            delete_one_image($recipe->thumbnail->small);
        }

        $recipe->thumbnail()->delete();
        $recipe->categories()->detach();
        $recipe->delete();
        return response()->json(["success" => true, "message" => "Recipe deleted"]);
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
            $img_normal = $img->resize(1000, null, function ($constraint) {
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
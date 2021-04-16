<?php

namespace App\Http\Controllers;

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
            $image_path = $request->file("image")->store("recipes", "s3");
            $recipe->image = Storage::disk("s3")->url($image_path);
        }

        if (!empty($request->input("content"))) {
            $data = convertBase64ToImageSrc($request->input("content"), "recipes");
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
            deleteImageForSingle($recipe->image);
            
            $image_path = $request->file("image")->store("recipes", "s3");
            $recipe->image = Storage::disk("s3")->url($image_path);
        }

        $recipe->name = $request->input("name");
        $recipe->subtitle = $request->input("subtitle");
        $recipe->content = $request->input("content");
        $recipe->save();

        $categories_id = [];
        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
        } 

        $recipe->categories()->sync($categories_id);

        return response()->json(["success" => true, "message" => "Recipe updated"]);
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        deleteImageForSingle($recipe->image);
        $recipe->categories()->detach();
        $recipe->delete();
        
        return response()->json(["success" => true, "message" => "Recipe deleted"]);
    }
}
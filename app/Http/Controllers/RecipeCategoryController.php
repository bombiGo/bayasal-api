<?php

namespace App\Http\Controllers;

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
            $image_path = $request->file("image")->store("recipe-categories", "s3");
            $category->image = Storage::disk("s3")->url($image_path);
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
            deleteImageForSingle($category->image);
            
            $image_path = $request->file("image")->store("recipe-categories", "s3");
            $category->image = Storage::disk("s3")->url($image_path);
        }

        $category->name = $request->input("name");
        $category->save();

        return response()->json(["success" => true, "message" => "Recipe category updated"]);
    }

    public function destroy($id)
    {
        $category = RecipeCategory::findOrFail($id);
        deleteImageForSingle($category->image);
        $category->recipes()->detach();
        $category->delete();
        return response()->json(["success" => true, "message" => "Recipe category deleted"]);
    }
}
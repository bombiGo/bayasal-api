<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use DB;

use App\Models\Course;
use App\Models\Info;
use App\Models\Recipe;
use App\Models\RecipeCategory;

class HelperController extends Controller
{
    public function getData() {
        $user = null;
        $courses = Course::with("thumbnail", "lessons")->get();
        $infos = Info::with("thumbnail", "categories")->get();
        $recipes = Recipe::with("thumbnail", "categories")->get();
        $recipe_categories = RecipeCategory::all();

        return response()->json([
            "courses" => $courses,
            "infos" => $infos,
            "recipes" => $recipes,
            "recipe_categories" => $recipe_categories,
            "user" => $user
        ]);
    }

    public function detailCourse($id)
    {
        $course = Course::findOrFail($id);
        $course["thumbnail"] = $course->thumbnail;
        $course["lessons"] = $course->lessons;
        return response()->json($course);
    }
    
    public function uploadEditor(Request $request)
    {
        $this->validate($request, [
            "file" => "required|image|max:2048",
        ]);

        $img = Image::make($request->file("file"));
        $img_name = md5(microtime(true)) . "." . $request->file("file")->extension();
        
        $img_normal = $img->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img_normal = $img_normal->stream();
        $img_path = "temp/" . $img_name;

        Storage::disk("s3")->put($img_path, $img_normal->__toString());

        $url = Storage::disk("s3")->url($img_path);

        return response()->json(["location" => $url]);
    }
}
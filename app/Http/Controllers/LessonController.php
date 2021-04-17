<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

use Storage;

class LessonController extends Controller
{
    public function store(Request $request, $id)
    {
        $rules = [
        	"day" => "required",  
            "title" => "required|unique:lessons,title|max:255",
            "is_locked" => "required|boolean",
            "mode" => "required"
        ];

        if ($request->input("mode") == "vimeo") {
        	$rules["video_id"] = "required";
        }

        if ($request->input("mode") == "pdf") {
            $rules["pdf_url"] = "required|mimes:pdf";
        }

        if ($request->input("mode") == "content") {
        	$rules["content"] = "required";
        }

        $this->validate($request, $rules);
          
        $lesson = new Lesson; 

        if ($request->hasFile("pdf_url") && $request->input("mode") == "pdf") {
            $file_path = $request->file("pdf_url")->store("lessons", "s3");
            $lesson->pdf_url = Storage::disk("s3")->url($file_path);
        } else {
        	$lesson->pdf_url = "";
        }

        if (!empty($request->input("content")) && $request->input("mode") == "content") {
            $data = convertBase64ToImageSrc($request->input("content"), "infos");
            $content = $data->saveHTML();
        } else {
            $content = "";
        }

        $lesson->title = $request->input("title");
        $lesson->day = $request->input("day");
        $lesson->is_locked = $request->input("is_locked");
        $lesson->mode = $request->input("mode");
        $lesson->duration = $request->input("duration");
        $lesson->video_id = $request->input("mode") == "vimeo" ? $request->video_id : "";
        $lesson->content = $content;
        $lesson->course_id =  $id;
        $lesson->save();

        return response()->json(["success" => true, "message" => "Lesson added"]);
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
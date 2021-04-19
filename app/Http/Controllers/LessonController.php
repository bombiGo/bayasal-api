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
        $lesson = Lesson::with("course")->findOrFail($id);
        return response()->json(["success" => true, "lesson" => $lesson]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "day" => "required",  
            "title" => "required|max:255|unique:lessons,title," . $id,
            "is_locked" => "required|boolean",
            "mode" => "required"
        ];

        if ($request->input("mode") == "vimeo") {
            $rules["video_id"] = "required";
        }

        if ($request->input("mode") == "pdf" && $request->hasFile("pdf_url")) {
            $rules["pdf_url"] = "required|mimes:pdf";
        }

        if ($request->input("mode") == "content") {
            $rules["content"] = "required";
        }

        $this->validate($request, $rules);
          
        $lesson = Lesson::findOrFail($id); 

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
        $lesson->save();

        return response()->json(["success" => true, "message" => "Lesson updated"]);
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        deleteImageForSingle($lesson->pdf_url);
        $lesson->delete();
        
        return response()->json(["success" => true, "message" => "Lesson deleted"]);
    }
}
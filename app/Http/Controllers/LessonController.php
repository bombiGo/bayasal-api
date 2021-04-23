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
            $data = convert_image_src_editor($request->input("content"), "lessons/editor");
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

        if ($request->input("mode") == "vimeo") {
            delete_one_image($lesson->pdf_url);
            delete_all_image_editor($lesson->content);

            $lesson->pdf_url = "";
            $lesson->content = "";
            $lesson->video_id = $request->input("video_id");
        }

        if ($request->input("mode") == "pdf") {
            delete_all_image_editor($lesson->content);

            if ($request->hasFile("pdf_url")) {
                delete_one_image($lesson->pdf_url);
                $file_path = $request->file("pdf_url")->store("lessons", "s3");
                $lesson->pdf_url = Storage::disk("s3")->url($file_path);    
            }

            $lesson->video_id = "";
            $lesson->content = "";
        }

        if ($request->input("mode") == "content") {
            delete_one_image($lesson->pdf_url);
            if (!empty($request->input("content"))) {
                $data = convert_image_src_editor($request->input("content"), "lessons/editor");
                $content = $data->saveHTML();

                $old_content = $lesson->content;
                if (!empty($old_content)) {
                    delete_all_image_old_editor($old_content, $content);    
                }
            } else {
                $content = "";
            }

            $lesson->video_id = "";
            $lesson->pdf_url = "";
            $lesson->content = $content;
        }

        $lesson->title = $request->input("title");
        $lesson->day = $request->input("day");
        $lesson->is_locked = $request->input("is_locked");
        $lesson->mode = $request->input("mode");
        $lesson->duration = $request->input("duration");
        $lesson->save();

        return response()->json(["success" => true, "message" => "Lesson updated"]);
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        delete_all_image_editor($lesson->content);
        delete_one_image($lesson->pdf_url);

        $lesson->delete();
        
        return response()->json(["success" => true, "message" => "Lesson deleted"]);
    }
}
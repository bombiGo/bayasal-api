<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\Course;

use Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with("lessons")->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $rules = [
            "title" => "required|unique:courses,title|max:255",
            "price" => "required|integer",
            "is_paid" => "required|boolean",
            "day" => "required|integer",
            "day_title" => "required",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $course = new Course; 

        if ($request->hasFile("image")) {
            $course->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "courses/editor");
            $content = $data->saveHTML();
        } else {
            $content = "";
        }

        $course->title = $request->input("title");
        $course->price = $request->input("price");
        $course->is_paid = $request->input("is_paid");
        $course->day = $request->input("day");
        $course->day_title = $request->input("day_title");
        $course->content = $content;
        $course->save();

        if ($request->hasFile("image")) {
            $course->thumbnail()->create([
                "small" => $this->get_upload_img_url("small", $request->file("image"))
            ]);
        }

        return response()->json(["success" => true, "message" => "Course added"]);
    }

    public function show($id)
    {
        $course = Course::with("lessons")->findOrFail($id);
        return response()->json(["success" => true, "course" => $course]);
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return response()->json(["success" => true, "course" => $course]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "title" => "required|max:255|unique:courses,title," . $id,
            "price" => "required|integer",
            "is_paid" => "required|boolean",
            "day" => "required|integer",
            "day_title" => "required",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
        
        $course = Course::findOrFail($id);

        if ($request->hasFile("image")) {
            delete_one_image($course->image);
            $course->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "courses/editor");
            $content = $data->saveHTML();

            $old_content = $course->content;
            if (!empty($old_content)) {
                delete_all_image_old_editor($old_content, $content);    
            }
        } else {
            $content = "";
        }

        $course->title = $request->input("title");
        $course->price = $request->input("price");
        $course->is_paid = $request->input("is_paid");
        $course->day = $request->input("day");
        $course->day_title = $request->input("day_title");
        $course->content = $content;
        $course->save();
        
        if ($request->hasFile("image")) {
            if (!empty($course->thumbnail->small)) {
                delete_one_image($course->thumbnail->small);
            }

            if ($course->thumbnail()->count() == 0) {
                $course->thumbnail()->create([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            } else {
                $course->thumbnail()->update([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            }
        }
        
        return response()->json(["success" => true, "message" => "Course updated"]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        delete_all_image_editor($course->content);
        delete_one_image($course->image);
        
        if (!empty($course->thumbnail->small)) {
            delete_one_image($course->thumbnail->small);
        }

        $course->thumbnail()->delete();
        $course->delete();
        
        return response()->json(["success" => true, "message" => "Course deleted"]);
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
            $img_path = "courses/thumbs/small_" . $img_name;

            Storage::disk("s3")->put($img_path, $img_small->__toString());
        } else {
            $img_normal = $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_normal = $img_normal->stream();
            $img_path = "courses/" . $img_name;

            Storage::disk("s3")->put($img_path, $img_normal->__toString());
        }
        
        $url = Storage::disk("s3")->url($img_path);
        return $url;
    }
}
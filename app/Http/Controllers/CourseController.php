<?php

namespace App\Http\Controllers;

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
            $image_path = $request->file("image")->store("courses", "s3");
            $course->image = Storage::disk("s3")->url($image_path);
        }

        if (!empty($request->input("content"))) {
            $data = convertBase64ToImageSrc($request->input("content"), "courses");
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
            deleteImageForSingle($course->image);
            
            $image_path = $request->file("image")->store("courses", "s3");
            $course->image = Storage::disk("s3")->url($image_path);
        }

        if (!empty($request->input("content"))) {
            $data = convertBase64ToImageSrc($request->input("content"), "courses");
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

        return response()->json(["success" => true, "message" => "Course updated"]);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        deleteImageForSingle($course->image);
        $course->delete();
        
        return response()->json(["success" => true, "message" => "Course deleted"]);
    }
}
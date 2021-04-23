<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\InfoCategory;

use Storage;

class InfoController extends Controller
{
    public function index()
    {
        $infos = Info::with("categories")->get();
        return response()->json($infos);
    }

    public function store(Request $request)
    {
        $rules = [
            "title" => "required|unique:infos,title|max:255",
            "subtitle" => "required|max:1000",
            "is_featured" => "required|boolean",
            "content" => "required"
        ];

        if ($request->hasFile("image")) {
            $rules["image"] = "image|max:2048";
        }

        $this->validate($request, $rules);
           
        $info = new Info; 

        if ($request->hasFile("image")) {
            $info->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "infos");
            $content = $data->saveHTML();
        } else {
            $content = "";
        }

        $info->title = $request->input("title");
        $info->subtitle = $request->input("subtitle");
        $info->is_featured = $request->input("is_featured");
        $info->content = $content;
        $info->save();

        if ($request->input("categories_id")) {
            $categories_id = explode(",", $request->input("categories_id"));
            $info->categories()->attach($categories_id);
        }

        if ($request->hasFile("image")) {
            $info->thumbnail()->create([
                "small" => $this->get_upload_img_url("small", $request->file("image"))
            ]);
        }

        return response()->json(["success" => true, "message" => "Info added"]);
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
            delete_one_image($info->image);
            $info->image = $this->get_upload_img_url("normal", $request->file("image"));
        }

        if (!empty($request->input("content"))) {
            $data = convert_image_src_editor($request->input("content"), "infos/editor");
            $content = $data->saveHTML();

            $old_content = $info->content;
            if (!empty($old_content)) {
                delete_all_image_old_editor($old_content, $content);    
            }
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

        if ($request->hasFile("image")) {
            if (!empty($info->thumbnail->small)) {
                delete_one_image($info->thumbnail->small);
            }

            if ($info->thumbnail()->count() == 0) {
                $info->thumbnail()->create([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            } else {
                $info->thumbnail()->update([
                    "small" => $this->get_upload_img_url("small", $request->file("image"))
                ]);
            }
        }

        return response()->json(["success" => true, "message" => "Info updated"]);
    }

    public function destroy($id)
    {
        $info = Info::findOrFail($id);
        delete_all_image_editor($info->content);
        delete_one_image($info->image);
        
        if (!empty($info->thumbnail->small)) {
            delete_one_image($info->thumbnail->small);
        }

        $info->thumbnail()->delete();
        $info->categories()->detach();
        $info->delete();
        
        return response()->json(["success" => true, "message" => "Info deleted"]);
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
            $img_path = "infos/thumbs/small_" . $img_name;

            Storage::disk("s3")->put($img_path, $img_small->__toString());
        } else {
            $img_normal = $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_normal = $img_normal->stream();
            $img_path = "infos/" . $img_name;

            Storage::disk("s3")->put($img_path, $img_normal->__toString());
        }
        
        $url = Storage::disk("s3")->url($img_path);
        return $url;
    }
}
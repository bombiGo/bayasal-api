<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\User;
use Storage;
use Cache;

class UserController extends Controller
{
    public function index()
    {
        $users = app("db")->select("SELECT * FROM users");
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "string", "min:6", "confirmed"],
        ];

        if ($request->hasFile("avatar")) {
            $rules["avatar"] = "image|max:512";
        }

        $this->validate($request, $rules);
           
        $user = new User; 

        if ($request->hasFile("avatar")) {
            $user->avatar = $this->get_upload_img_url("normal", $request->file("avatar"));
        }

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->phone = $request->input("phone");
        $user->password = app("hash")->make($request->input("password"));
        $user->save();

        return response()->json(["success" => true, "message" => "User added"]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(["success" => true, "user" => $user]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users,email," . $id]
        ];

        if ($request->input("password")) {
            $rules["password"] = ["required", "string", "min:6", "confirmed"];
        }

        if ($request->hasFile("avatar")) {
            $rules["avatar"] = "image|max:512";
        }

        $this->validate($request, $rules);
        
        $user = User::findOrFail($id); 

        if ($request->hasFile("avatar")) {
            delete_one_image($user->avatar);
            $user->avatar = $this->get_upload_img_url("normal", $request->file("avatar"));
        }

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->phone = $request->input("phone") ? $request->input("phone") : "";

        if (!empty($request->input("password"))) {
            $user->password = app("hash")->make($request->input("password"));
        }

        $user->save();

        return response()->json(["success" => true, "message" => "User updated"]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        delete_one_image($user->avatar);
        $user->delete();
        return response()->json(["success" => true, "message" => "User deleted"]);
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
            $img_path = "users/thumbs/small_" . $img_name;

            Storage::disk("s3")->put($img_path, $img_small->__toString());
        } else {
            $img_normal = $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img_normal = $img_normal->stream();
            $img_path = "users/" . $img_name;

            Storage::disk("s3")->put($img_path, $img_normal->__toString());
        }
        
        $url = Storage::disk("s3")->url($img_path);
        return $url;
    }
}

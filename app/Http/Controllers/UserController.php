<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

use Cache;

class UserController extends Controller
{
    public function index()
    {
        $users = app("db")->select("SELECT * FROM users");
        return response()->json($users);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile("file")) {
            $image = Image::make($request->file("file"));
            $image->save($request->file("file")->getClientOriginalName());
            
            return "uploaded";
        }
    }

    public function getImage()
    {
        Cache::flush();
        
        $img = Image::cache(function($image) {
            $image->make("http://bayasal.local/img1.jpg");
        });
        
        return response($img)->header("Content-Type", "image/jpg");
            // ->header('Pragma','public')
            // ->header('Content-Disposition','inline; filename="qrcodeimg.png"')
            // ->header('Cache-Control','max-age=60, must-revalidate');
    }
}

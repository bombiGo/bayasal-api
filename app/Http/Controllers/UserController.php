<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        $users = app("db")->select("SELECT * FROM users");
        return response()->json($users);
    }
}

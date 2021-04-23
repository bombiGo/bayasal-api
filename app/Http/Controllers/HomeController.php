<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use DB;

class HomeController extends Controller
{	
	public function dashboard() {
		$usersCount = DB::table("users")->count();
		$coursesCount = DB::table("courses")->count();
		$lessonsCount = DB::table("lessons")->count();
		$infosCount = DB::table("infos")->count();
		$recipesCount = DB::table("recipes")->count();

		return response()->json([
			"users_count" => $usersCount,
			"courses_count" => $coursesCount,
			"lessons_count" => $lessonsCount,
			"infos_count" => $infosCount,
			"recipes_count" => $recipesCount
		]);
	}
}


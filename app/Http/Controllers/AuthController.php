<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
        ]);

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app("hash")->make($request->password);
            $user->role = "user";
            $user->phone = "";
            $user->save();

            return response()->json(["user" => $user, "message" => "CREATED"], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "User Registration Failed!"], 409);
        }
    }
    
    public function login(Request $request)
    {
        if ($request->has("role")) {
            $credentials = request(["email", "password", "role"]);
        } else {
            $credentials = request(["email", "password"]);
        }
        
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = Auth::user();        
        return response()->json(["success" => true, "user" => $user]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(["message" => "Successfully logged out"]);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            "user" => Auth::user(),
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $input = $request->all();
        $userId = Auth::user()->id;
        
        $rules = array(
            "old_password" => "required",
            "new_password" => "required|min:6",
            "confirm_password" => "required|same:new_password",
        );

        $validator = Validator::make($input, $rules);
        
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request("old_password"), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "The old password is wrong");
                } else if ((Hash::check(request("new_password"), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "The new password is the same as the old password");
                } else {
                    User::where("id", $userId)->update(["password" => Hash::make($input["new_password"])]);
                    $arr = array("status" => 200, "message" => "Your password has been successfully changed. You can log in now");
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg);
            }
        }
        return response()->json($arr);
    }

    public function updateUser(Request $request)
    {
        $userId = Auth::user()->id;

        $user = User::findOrFail($userId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(["status" => true, "user" => $user]);
    }
}

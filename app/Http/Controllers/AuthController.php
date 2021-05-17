<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Validator;
use DB;

class AuthController extends Controller
{
    public $qpay_url = "https://merchant-sandbox.qpay.mn";
    public $qpay_username = "TEST_MERCHANT";
    public $qpay_password = "123456";

    public function register(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
        ]);

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app("hash")->make($request->password);
            $user->role = "user";
            $user->phone = "";
            $user->save();

            return response()->json(["user" => $user, "message" => "User created"]);
        } catch (\Exception $e) {
            return response()->json(["message" => "User Registration Failed!"]);
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
                if ((app("hash")->check(request("old_password"), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Хуучин нууц үг буруу байна!");
                } else if ((app("hash")->check(request("new_password"), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Шинэ нууц үг тохирохгүй байна!");
                } else {
                    User::where("id", $userId)->update(["password" => app("hash")->make($input["new_password"])]);
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
        $this->validate($request, [
            "name" => "required|min:2",
            "email" => "required|email"
        ]);

        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        if ($request->has("avatar") && $request->avatar && $request->avatar_name) {
            delete_one_image($user->avatar);

            $file_content = base64_decode($request->avatar);
            $mime_type = explode(".", $request->avatar_name)[1];
            $img_path = "avatars/". str_random(40).'.'.$mime_type;
            $user->avatar = $this->get_upload_img_url($img_path, $file_content);
        } 

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return response()->json(["success" => true, "user" => $user]);
    }

    public function get_upload_img_url($img_path, $file_content)
    {
        $img = Image::make($file_content);
        $img_normal = $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img_normal = $img_normal->stream();
        Storage::disk("s3")->put($img_path, $img_normal->__toString());
        $url = Storage::disk("s3")->url($img_path);

        return $url;
    }

    public function createOrder(Request $request)
    {
        // $this->validate($request, [
        //     "course_id" => "required",
        //     "user_id" => "required"
        // ]);

        // $order = new Order;
        // $order->user_id = $request->user_id;
        // $order->user_name = $request->user_name;
        // $order->user_email = $request->user_email;
        // $order->course_id = $request->course_id;
        // $order->course_title = $request->course_title;
        // $order->course_day = $request->course_day;
        // $order->sender_invoice_no = $request->sender_invoice_no;
        // $order->course_price = $request->amount;
        // $order->status = "pending";
        // $order->save();
        
        return $this->createInvoice($request->invoice_code, $request->sender_invoice_no, $request->invoice_receiver_code, $request->invoice_description, $request->amount, $request->callback_url);
    }

    public function createInvoice($invoice_code, $sender_invoice_no, $invoice_receiver_code, $invoice_description, $amount, $callback_url)
    {
        $qpay = DB::table("qpay")->get();

        $access_token = "access_token";
        $refresh_token = "refresh_token";
        $is_recursive = false;
        $msg = "access_token is working";

        if (count($qpay) == 1) {
            $access_token = $qpay[0]->access_token ? $qpay[0]->access_token : $access_token;
            $refresh_token = $qpay[0]->refresh_token ? $qpay[0]->refresh_token : $refresh_token;
        }

        $form_data = '{
            "invoice_code": "TEST_INVOICE",
            "sender_invoice_no": "'.$sender_invoice_no.'",
            "invoice_receiver_code": "'.$invoice_receiver_code.'",
            "invoice_description": "'.$invoice_description.'",
            "amount": "'.$amount.'",
            "callback_url": "'.$callback_url.'"
        }';

        return $this->send_request_create_invoice($form_data, $access_token, $refresh_token, $is_recursive, $msg);
    }

    public function send_request_create_invoice($form_data, $access_token, $refresh_token, $is_recursive, $msg)
    {
        $headers = array(
            "Content-Type:application/json",
            "Authorization: Bearer " . $access_token
        );

        $result = rest_call($this->qpay_url . "/v2/invoice", $headers, $form_data);
        
        if ($result["http_code"] == 401) {
            if (!$is_recursive) {
                return $this->call_func_with_refresh_token("create_invoice_func", $form_data, $refresh_token);
            }
        }

        return $result;
    }

    public function send_request_check_payment($form_data, $access_token, $refresh_token, $is_recursive, $msg) {

    }

    public function call_func_with_refresh_token($func_name, $form_data, $refresh_token) {
        $headers = array(
            "Content-Type:application/json",
            "Authorization: Bearer " . $refresh_token
        );

        $result = rest_call($this->qpay_url . "/v2/auth/refresh", $headers);

        if ($result["http_code"] == 200) {
            return $result;
            // $this->save_token_to_db()
        } else {
            return $this->create_auth_token();
        }
    }
 
    public function create_auth_token() {
        $headers = array(
            "Content-Type:application/json",
            "Authorization: Basic " . base64_encode("$this->qpay_username:$this->qpay_password")
        );

        $result = rest_call($this->qpay_url . "/v2/auth/token", $headers);

        if ($result["http_code"] == 200) {

        }
        
        return $result;
    }

    public function call_func_name($func_name, $form_data, $access_token, $refresh_token) {
        $msg = "refresh token is working";

        if ($func_name == "create_invoice_func") {
            return $this->send_request_create_invoice($form_data, $access_token, $refresh_token, true, $msg);
        } else if ($func_name == "check_payment_func") {
            return $this->send_request_check_payment($form_data, $access_token, $refresh_token, true, $msg);
        }
    }

}

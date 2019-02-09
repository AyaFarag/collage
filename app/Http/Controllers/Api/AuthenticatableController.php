<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;

use App\Events\ConfirmIdentityByPhone;
use App\Events\ForgotPassword;

use Carbon\Carbon;

use Hash;

abstract class AuthenticatableController extends Controller
{
    public function changePassword(ChangePasswordRequest $request){
        $user = $this -> guard() -> user();
        if(Hash::check($request -> input("old_password"), $user -> password)) {
            $user -> password = $request -> input("password");
            $user -> save();
            return response() -> json(["message" => trans("api.password_changed_successfully")], 200);
        }
        return response() -> json(["error" => trans("api.invalid_password")], 422);
    }

    public function login(LoginRequest $request) {
        if ($this -> loginGuard() -> attempt($request -> only($this -> getUsernameAttribute(), "password"))) {
            $teacher = $this -> loginGuard() -> user();
            $teacher -> api_token = str_random(60);
            $teacher -> device_token = $request -> input("device_token");
            $teacher -> save();
            return $this -> sendLoginResponse($teacher);
        }
        return response() -> json(["error" => trans("auth.failed")], 401);
    }

    public function forgetPassword(Request $request) {
        $user = $this -> findUserOrFail($request);
        if ($user -> phone_confirmation_code !== $request -> input("code"))
            return response() -> json(["error" => trans("api.invalid_code")], 403);

        $token = event(new ForgotPassword($user))[0];

        return response() -> json(compact("token"));
    }

    public function resetPassword($token, ResetPasswordRequest $request) {
        $user = $this -> findUserOrFail($request);
        if ($user -> passwordReset && Hash::check($token, $user -> passwordReset -> token)) {        
            $user -> password                = $request -> input("password");
            $user -> passwordReset() -> delete();
            $user -> save();
            return response() -> json(["message" => trans("api.reset_successfully")], 200);
        }
        return response() -> json(["error" => trans("api.invalid_token")], 403);
    }

    public function requestPhoneIdentityConfirm(Request $request) {
        $user = $this -> findUserOrFail($request);

        if (
            Carbon::parse($user -> phone_code_created_at)
                -> gt(Carbon::now() -> subMinutes(config("app.sms-rate-limit-minutes")))
        ) {
            return response() -> json([
                "seconds_left" => Carbon::parse($user -> phone_code_created_at)
                    -> addMinutes(config("app.sms-rate-limit-minutes"))
                    -> diffInSeconds(Carbon::now())
            ], 429);
        }

        event(new ConfirmIdentityByPhone($user));

        return response() -> json(["message" => trans("api.sent_successfully")]);
    }

    public function getUsernameAttribute() {
        return "phone";
    }

    private function findUserOrFail($request) {
        $attr = $this -> getUsernameAttribute();
        return call_user_func([$this -> model(), "where"], $attr, $request -> input($attr)) -> firstOrFail();
    }

    abstract public function model();
    abstract public function loginGuard();
    abstract public function guard();
    abstract public function sendLoginResponse($user);
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassRequest;
use App\Models\PasswordResetTokens;
use App\Models\User;
use App\Notifications\sendCodeNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Mail;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(Request $request) {
        try {
            $user = User::whereEmail($request->email)->get();
            if (count($user)>0) {
                $user = User::whereEmail($request->input('email'))->first();
                if ($user) {
                    $data['key'] = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                    $data['title'] = "Reset Password";
                    $data['body'] = "Code to re-appoint the password";
                    $user->notify(new sendCodeNotification($data));
                    $passResToken = PasswordResetTokens::whereEmail($request->email)->delete();
                    PasswordResetTokens::create(
                        [
                            'email' => $request->email,
                            'key' => $data['key'],
                            'created_at' => Carbon::now(),
                        ]
                    );
                    return $this->returnSuccess('Mail send successfully',200);
                }
            } else {
                return $this->returnError('user is not found!',200);
            }
        } catch (Exception $e) {
            return $this->returnError($e->getMessage(),200);
        }
    }

    public function resetPasswordLoad(ResetPassRequest $request) {
        $resetData = PasswordResetTokens::where('key',$request->key)->where('email',$request->email)->first();
        if ($request->key != '' && $resetData ) {
            $user = User::where('email',$request->email)->first();
            $user->password = Hash::make($request->input('password'));
            $user->save();
            $resetData = PasswordResetTokens::where('key',$request->key)->where('email',$request->email)->delete();
            return $this->returnSuccess('Change Password Successfully',200);
        }else {
            return $this->returnError('Page 404',404);
        }
    }

}

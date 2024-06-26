<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
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

                    return response()->json(['error'=> false, 'message' => 'Mail send successfully','key'=>$data['key']],200);

                }

            } else {
                return response()->json(['error'=> true, 'message' => 'user is not found!'],200);
            }
                
        } catch (Exception $e) {
            return response()->json(['error'=> true, 'message' => $e->getMessage()],200);
        }
    }

    public function resetPasswordLoad(Request $request) {
        $resetData = PasswordResetTokens::where('key',$request->key)->where('email',$request->email)->first();
        if (isset($request->key) && $resetData ) {

            $validator = Validator::make($request->all(),['password' => 'required|min:6'],);
            if ($validator->failed()) return response()->json(['error'=> true, 'message' => $validator->errors()],200);
            
            $user = User::where('email',$request->email)->first();
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $resetData = PasswordResetTokens::where('key',$request->key)->where('email',$request->email)->delete();

            return response()->json(['error'=> false, 'message' => 'Change Password Successfully'],200);
        }else {
            return response()->json(['error'=> true, 'message' => 'Page 404'],404);
        }
    }

}

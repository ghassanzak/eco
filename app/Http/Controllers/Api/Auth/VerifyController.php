<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Notifications\sendCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Mail;


class VerifyController extends Controller
{
    
    public function sendCodeMail() {
        if (auth()->user()) {
            $user = User::where('email',auth()->user()->email)->first();
            if ($user) {

                $data['key'] = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                $data['title'] = "Email Verification";
                $data['body'] = "Pleade click here to below to verify your mail";
                $user->notify(new sendCodeNotification($data));
                $user->remember_token = $data['key'];
                $user->save();
                return $this->returnSuccess('Mail send successfully',200);
            } else {
                return $this->returnError('user is not found!',200);
            }
        } else {
            return $this->returnError('user is not Authentication',200);
        }
    }

    function verificationMail(Request $request) {

        $validator = Validator::make($request->all(),['key' => 'required|numeric|min:99999|max:99999999'],);
        if ($validator->failed()) return $this->returnError($validator->errors(),200);

        if (auth()->check()) {
            $user = User::where('remember_token', $request->input('key'))->where('email',auth()->user()->email)->first();
            if($user){
    
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                $user->remember_token = null;
                $user->email_verified_at = $datetime;
                $user->save();
            }else{
                return $this->returnError('The entrance code is not valid',200);
            }
    
            return $this->returnSuccess('Email verified successfully.',200);
        } else {
            return $this->returnError('user is not Authentication',200);
        }

    }

}

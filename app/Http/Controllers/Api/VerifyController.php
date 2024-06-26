<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\Carbon;
use Mail;


class VerifyController extends Controller
{
    
    public function sendCodeMail(Request $request) {
        if (auth()->user()) {
            $user = User::where('email',$request->input('email'))->get();
            if (count($user)>0) {

                $key = Str::random(40);
                $code = Str::random(6);
                $domain = URL::to('/');
                
                
                $data['code'] = $code;
                $data['email'] = $request->input('email');
                $data['title'] = "Email Verification";
                $data['body'] = "Pleade click here to below to verify your mail";

                Mail::send('verifyMail',['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $user = User::find($user[0]['id']);
                $user->remember_token = $key;
                $user->codeVerify = $code;
                $user->save();

                return response()->json(['error'=> false, 'message' => 'Mail send successfully','key'=> $key],200);

            } else {
                return response()->json(['error'=> true, 'message' => 'user is not found!'],200);
            }
            
        } else {
            return response()->json(['error'=> true, 'message' => 'user is not Authentication'],200);
        }
        
    }

    function verificationMail(Request $request) {
        if($this->codeChecking($request)){

            $user = User::where('remember_token', $request->input('key'))->get();
            $user = User::find($user[0]['id']);

            
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user->remember_token = null;
            $user->codeVerify = null;
            $user->email_verified_at = $datetime;
            $user->save();
        }else{
            return response()->json(['error'=> true, 'message' => 'The entrance code is not valid'],200);
        }

        return response()->json(['error'=> false, 'message' => 'Email verified successfully.'],200);
        
    }

    public function codeChecking($request) {
        $user = User::where('remember_token', $request->input('key'))->get();
        if (count($user)>0) {
            
            $user = User::find($user[0]['id']);

            $codeArr = array( "key" =>  $user->remember_token, "code" => $user->codeVerify,);

            return $this->isValidateCodeToken($codeArr,$request->input('key'),$request->input('code'));
        } else {
            return response()->json(['error'=> true, 'message' => 'Page not found.'],404);
        }
    }

    function isValidateCodeToken($codeArr,$key,$code) {
        return ($key == $codeArr['key'] && $code == $codeArr['code'])?true:false;
    }
}

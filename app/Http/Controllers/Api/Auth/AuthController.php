<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Image;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\Carbon;
use Mail;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthController extends Controller
{

    function login(LoginRequest $request) {
        $credentials = request(['email','password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return $this->respondWithToken($token);
    }
    public function refresh()
    {   
        $tokenOld = JWTAuth::getToken();
        if (!$token = auth('api')->refresh()) {
            JWTAuth::invalidate($tokenOld);
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return $this->respondWithToken($token);
    }
    public function me()
    {
        return response()->json(auth('api')->user());
    }
    function logout() {
        $tokenOld = JWTAuth::getToken();
        try {
            auth('api')->logout();
            // invalidate token
            JWTAuth::invalidate($tokenOld);
            return response()->json(['error'=> false, 'message' => 'logout successfuly'],200);
            
        } catch (\Exception $e) {
            return response()->json(['error'=> false, 'message' => $e->getMessage()],200);
            
        }
        
    }
    public function register(RegisterRequest $request){
        $date['first_name']    =  $request->first_name;
        $date['last_name']     =  $request->last_name;
        $date['phone_one']     =  $request->phone_one;
        $date['phone_two']     =  $request->phone_two;
        $date['email']         =  $request->email;
        $date['address']       =  $request->address;
        $date['password']      = Hash::make($request->password);
        $date['status']        =  $request->status;
        $date['is_admin']      =  $request->is_admin;

        if ($request->photo) {
            $filename = $request->first_name . time().'-'.'.'.$request->photo->getClientOriginalExtension();
            $path = public_path('assets/users');
            $request->photo->move($path, $filename);
            $date['photo'] = $filename;
        }
        $user = User::create($date);

        if (!$token = auth('api')->login($user)) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return $this->respondWithToken($token);
    }
    public function changePassword(ChangePassRequest $request){
        $hasPass=  Hash::make($request->newPass);
        $checkCurrentPass=Hash::check($request->currentPass, auth('api')->user()->password);
        if(!$checkCurrentPass){
            return response()->json(['error'=> true, 'message' => 'The current password is incorrect'],200);
        }
        User::where('email',auth('api')->user()->email)->update(['password'=>$hasPass]);
        return response()->json(['error'=> false, 'message' => 'Password Successfully Changed'],200);
    }

    function respondWithToken($token) {
        return response()->json([
            'error'=> false,
            'access_token'=>$token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600*70,
        ]);
    }
}

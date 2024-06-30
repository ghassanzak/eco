<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthController extends Controller
{

    public string $file_media = "assets/users/";

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

        if (isset($request->photo)) {
            $date['photo'] = $this->Image($request->file('image'),$this->file_media);
        }
        $user = User::create($date);

        if (!$token = auth('api')->login($user)) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return $this->respondWithToken($token);
    }
    
    function respondWithToken($token) {
        return response()->json([
            'error'=> false,
            'access_token'=>$token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600*70,
        ]);
    }

}

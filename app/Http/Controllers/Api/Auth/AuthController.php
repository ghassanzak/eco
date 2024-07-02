<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Resources\UserInfoResourse;
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
            return $this->returnError('An error in the entered data',404);
        }
        return $this->returnData('data',$this->respondWithToken($token),auth()->user()->is_admin==1?'Type User(admin)':'Type User(not admin)') ;
    }
    public function refresh()
    {
        return $this->refreshToken();
    }
    public function me()
    {
        $user = new UserInfoResourse(auth('api')->user());
        return $this->returnSuccess($user,200);
    }
    function logout() {
        $tokenOld = JWTAuth::getToken();
        try {
            auth('api')->logout();
            // invalidate token
            JWTAuth::invalidate($tokenOld);
            return $this->returnSuccess('logout successfuly',200);
            
        } catch (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e) {
            
            return $this->returnError($e->getMessage(),404);
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
        $date['status']        =  1;
        $date['is_admin']      =  0;

        if (isset($request->photo)) {
            $date['photo'] = $this->Image($request->file('photo'),$this->file_media);
        }
        $user = User::create($date);

        if (!$token = auth('api')->login($user)) {
            return $this->returnError('An error in the entered data',200);
        }
        return $this->returnData('data',$this->respondWithToken($token),auth()->user()->is_admin==1?'Type User(admin)':'Type User(not admin)');
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    function login(Request $request) {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8'
        ],
    );
        if ($validator->failed()) {
            return response()->json(['error'=> true, 'message' => $validator->errors()],200);
        }


        $credentials = request(['email','password']);

        
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return response()->json([
            'access_token'=>$token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600,
        ]);
        
    }

    public function me()
    {
        # Here we just get information about current user
        return response()->json(auth('api')->user());
    }

    function logout() {
        $tokenOld = JWTAuth::getToken();        
        // invalidate token
        $invalidate = JWTAuth::invalidate($tokenOld);

        auth('api')->logout();
        return response()->json(['error'=> false, 'message' => 'logout successfuly'],200);
    }


    public function refresh()
    {   
        $tokenOld = JWTAuth::getToken();
        
        $token = auth('api')->refresh();
        
        // invalidate token
        $invalidate = JWTAuth::invalidate($tokenOld);
        
        if (!$token) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        
        return response()->json([
            'token' => $token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600,
            
        ]);
    }
    
    public function register(Request $request){
        $request->validate([
            'first_name'    =>  'required|string|max:255',
            'last_name'     =>  'required|string|max:255',
            'phone_one'     =>  'required|numeric|min:99999|max:9999999999',
            'phone_two'     =>  'numeric|min:99999|max:9999999999',
            'email'         =>  'required|string|email|max:255|unique:users,email',
            'password'      =>  'required|string|min:6',
            'photo'         =>  'mimes:jpg,png|image',
            'address'       =>  'string|max:255',
            // 'photo'         =>  ['required', File::image()->min(1024)->max(12 * 1024)->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),],
            'status'        =>  'boolean|numeric|min:0|max:1',
            'is_admin'      =>  'boolean|numeric|min:0|max:1',
        ]);

        
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
            $filename = time().'-'.'.'.$request->photo->getClientOriginalExtension();
            $path = public_path('assets/users');
            $request->photo->move($path, $filename);
            $date['photo']         =  $filename;
        }

        $user = User::create($date);
        

        $token = auth('api')->login($user);
        if (!$token) {
            return response()->json(['error'=> true, 'message' => 'Unauthorized'],200);
        }
        return response()->json([
            'access_token'=>$token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600,
        ]);
    }

    public function changePassword(Request $request){

        $hasPass=  Hash::make($request->newPass);
        $checkCurrentPass=Hash::check($request->currentPass, auth('api')->user()->password);
        if(!$checkCurrentPass){
            return response()->json(['error'=> true, 'message' => 'The current password is incorrect'],200);
        }
        else
        {
            User::where('email',auth('api')->user()->email)->update(['password'=>$hasPass]);
            return response()->json(['error'=> false, 'message' => 'Password Successfully Changed'],200);
        }
    }

    

}

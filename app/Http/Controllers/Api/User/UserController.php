<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public string $file_media = "assets/users/";
    public function changePassword(ChangePassRequest $request){
        $hasPass=  Hash::make($request->newPass);
        $checkCurrentPass=Hash::check($request->currentPass, auth('api')->user()->password);
        if(!$checkCurrentPass){
            return $this->returnError('The current password is incorrect',200);
        }
        User::where('email',auth('api')->user()->email)->update(['password'=>$hasPass]);
        return $this->returnSuccess('Password Successfully Changed',200);
    }
    public function updateProfile(UpdateInfoRequest $request){
        if(isset($request->first_name))    $date['first_name']    =  $request->first_name;
        if(isset($request->last_name))     $date['last_name']     =  $request->last_name;
        if(isset($request->phone_one))     $date['phone_one']     =  $request->phone_one;
        if(isset($request->phone_two))    $date['phone_two']     =  $request->phone_two;
        if(isset($request->address))       $date['address']       =  $request->address;
        if(isset($request->status))        $date['status']        =  $request->status;


        if(isset($request->email)){
            $date['email']              =  $request->email;
            $date['email_verified_at']  =  null;
        }
        // dd($request->is_admin);
        if(isset($request->is_admin)){
            if(auth()->user()->is_admin == 1){
                $date['is_admin']  =  $request->is_admin;
            }
        } 

        if (isset($request->photo)) {
            $this->removeImage(auth()->user()->photo);
            $date['photo'] = $this->Image($request->file('photo'),$this->file_media);
        }
        if(isset($date)) $user = User::where('id',auth()->user()->id)->update($date);
        else return $this->returnSuccess('nothing update data',200);

        if ($user) {
            return $this->returnSuccess('Update Profile Successfully',203);
        }
        return $this->returnError('Unauthorized',403);
    }
}

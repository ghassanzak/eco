<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\IdRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public string $file_media = "assets/users/";
    public function updateUser(UpdateUserRequest $request){
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
        if(isset($request->is_admin)){
            if(auth()->user()->is_admin == 1){
                $date['is_admin']  =  $request->is_admin;
            }
        } 

        if (isset($request->photo)) {
            $this->removeImage(auth()->user()->photo);
            $date['photo'] = $this->Image($request->file('photo'),$this->file_media);
        }
        if(isset($date)) $user = User::where('id',$request->id)->update($date);
        else return $this->returnSuccess('nothing update data',200);

        if ($user) {
            return $this->returnSuccess('Update Profile is User Successfully',203);
        }
        return $this->returnError('Unauthorized',403);
    }
    public function deleteUser(IdRequest $request)
    {
        $user = User::where('id',$request->id)->first();
        if ($user) {
            $this->removeImage($user->photo);
            if(auth()->user()->is_admin == 1) $user->delete();
            return $this->returnSuccess('User deleted successfully',200);
        }
        return $this->returnError('Something was wrong',200);
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInfoRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct() {
        
    }
    public function update_info(UpdateInfoRequest $request)
    {
        if(isset($request->first_name)) $date['first_name'] =  $request->first_name;
        if(isset($request->last_name))  $date['last_name']  =  $request->last_name;
        if(isset($request->phone_one))  $date['phone_one']  =  $request->phone_one;
        if(isset($request->phone_two))  $date['phone_two']  =  $request->phone_two;
        if(isset($request->email))      $date['email']      =  $request->email;
        if(isset($request->address))    $date['address']    =  $request->address;
        if(isset($request->status))     $date['status']     =  $request->status;
        if(isset($request->photo)) {
            $photo = auth()->user()->user->photo;
            if ($photo) {
                if (file_exists('assets/users/' . $photo)) {
                    unlink('assets/users/' . $photo);
                }
            }
            $filename = $request->first_name . time().'-'.'.'.$request->photo->getClientOriginalExtension();
            $path = public_path('assets/users');
            $request->photo->move($path, $filename);
            $date['photo'] = $filename;
        }
        User::where('id',auth()->user()->id)->update($date);
    }
}

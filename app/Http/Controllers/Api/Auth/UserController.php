<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
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
    public function update_info(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name'    =>  'required|string|max:255',
            'last_name'     =>  'required|string|max:255',
            'phone_one'     =>  'required|numeric|min:99999|max:9999999999',
            'phone_two'     =>  'numeric|min:99999|max:9999999999',
            'email'         =>  'required|string|email|max:255|unique:users,email',
            'photo'         =>  'mimes:jpg,png|image',
            'address'       =>  'string|max:255',
            // 'photo'         =>  ['required', File::image()->min(1024)->max(12 * 1024)->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),],
            'status'        =>  'boolean|numeric|min:0|max:1',
            'is_admin'      =>  'boolean|numeric|min:0|max:1',
        ]);
        if($validation->fails()){return response()->json(['errors' => true, 'messages' => $validation->errors()],200);}
        
        $date['first_name']    =  $request->first_name;
        $date['last_name']     =  $request->last_name;
        $date['phone_one']     =  $request->phone_one;
        $date['phone_two']     =  $request->phone_two;
        $date['email']         =  $request->email;
        $date['address']       =  $request->address;
        $date['status']        =  $request->status;
        if (auth()->user()->user->is_admin()) {
            $date['is_admin']      =  $request->is_admin;
        }

        if ($request->photo) {
            $photo = auth()->user()->user->photo;
            if ($photo) {
                
                if (file_exists('assets/users/' . $photo)) {
                    
                    unlink('assets/users/' . $photo);
                }
            }
            $filename = time().'-'.'.'.$request->photo->getClientOriginalExtension();
            $path = public_path('assets/users');
            $request->photo->move($path, $filename);
            $date['photo'] = $filename;
        }

        $user = auth()->user()->user->create($date);
    }
}

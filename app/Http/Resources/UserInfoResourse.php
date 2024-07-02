<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (auth()->user()->is_admin == 1) {
            return [
                'id'                =>$this->id,
                'first_name'        =>$this->first_name,
                'last_name'         =>$this->last_name,
                'phone_one'         =>$this->phone_one,
                'phone_two'         =>$this->phone_two,
                'email'             =>$this->email,
                'address'           =>$this->address,
                'photo'             =>$this->photo,
                'status'            =>$this->status,
                'is_admin'          =>$this->is_admin,
                'email_verified_at' =>$this->email_verified_at,
                'codeVerify'        =>$this->codeVerify,
                'remember_token'    =>$this->remember_token,
                'created_at'        =>$this->created_at,
                'updated_at'        =>$this->updated_at,
            ];
        }
        return [
            'first_name'        =>$this->first_name,
            'last_name'         =>$this->last_name,
            'phone_one'         =>$this->phone_one,
            'phone_two'         =>$this->phone_two,
            'email'             =>$this->email,
            'address'           =>$this->address,
            'photo'             =>$this->photo,
            'created_at'        =>$this->created_at,
        ];
    }
}

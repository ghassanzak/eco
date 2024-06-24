<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'phone_one' => '+963999999999',        
            'phone_two' => '+963999999991',
            'email'=>'admin@gmail.com',
            'address'=> 'Dama',
            'password'=>Hash::make('123456789'),
            'status' => 1,
            'is_admin' => 1,
        ]);
        User::create([
            'first_name' => 'user',
            'last_name' => 'user',
            'phone_one' => '+963999999999',        
            'phone_two' => '+963999999991',
            'email'=>'user@gmail.com',
            'address'=> 'Dama',
            'password'=>Hash::make('123456789'),
            'status' => 1,
            'is_admin' => 0,
        ]);
        User::create([
            'first_name' => 'user2',
            'last_name' => 'user2',
            'phone_one' => '+963999999999',        
            'phone_two' => '+963999999991',
            'email'=>'user2@gmail.com',
            'address'=> 'Dama',
            'password'=>Hash::make('123456789'),
            'status' => 1,
            'is_admin' => 0,
        ]);


    }
}

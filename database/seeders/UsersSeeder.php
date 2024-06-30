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
        $users = [];
        for ($i=1; $i < 4; $i++) { 
            
            $users[] = [
                'first_name' => 'user'.$i,
                'last_name' => 'user'.$i,
                'phone_one' => '+9639999999'.$i,  
                'phone_two' => '+9639999999'.$i,
                'email'=>'user'.$i.'@gmail.com',
                'address'=> 'Dama',
                'password'=>Hash::make('123456789'),
                'status' => rand(0,1),
                'is_admin' => 0,
            ];

        }

        $chunks = array_chunk($users, 4);
        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }

    }
}

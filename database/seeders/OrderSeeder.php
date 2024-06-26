<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        for ($i=0; $i < 20; $i++) { 
            $user = User::where('id', '>', 1)->inRandomOrder()->first();
            $product = Product::inRandomOrder()->first();
            $quant = rand(1,5);
            $price = $quant * $product->current_sale_price;
            Order::create([

                'name'=>$user->first_name,
                'email'=>$user->email,
                'phone'=>$user->phone_one,
                'address'=>$user->address,
                'product_id'=>$product->id,
                'user_id'=>$user->id,
                'product_name'=>$product->name,
                'quantity'=>$quant,
                'price'=>$price,
                'payment_status'=>rand(0,1),
                'delivery_status'=>rand(0,1),

            ]);

        }
    }
}

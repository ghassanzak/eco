<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
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
            Cart::create([

                'name'      =>'',
                'slug'      =>'',
                'user_id'       =>'',
                'code'      =>'',
                'brand'     =>'',
                'current_purchase_cost'     =>'',
                'current_sale_price'        =>'',
                'available_quantity'        =>'',
                'description'       =>'',
                'is_popular'        =>'',
                'is_trending'       =>'',
                'status'        =>'',
                'category_id'       =>'',

            ]);

        }
    }
}

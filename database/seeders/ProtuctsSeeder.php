<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


class ProtuctsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Factory::create();

    


        $product = [];
        $categories = collect(Category::all()->modelKeys());
        $user = collect(User::where('id', '>', 2)->get()->modelKeys());

        for ($i = 0; $i < 300; $i++) {
            $days = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'];
            $months = ['01', '02', '03', '04', '05', '06', '07', '08'];
            $product_date = "2023-" . Arr::random($months) . "-" . Arr::random($days) . " 01:01:01";
            $product_name = $faker->sentence(mt_rand(2, 4), true);
            $sal_cost = rand(20,500);
            $sal_price = $sal_cost + 10;


            $product[] = [
                'name'         => $product_name,
                'slug'          => Str::slug($product_name),
                'description'   => $faker->paragraph(),
                'current_purchase_cost' =>$sal_cost,
                 'current_sale_price' =>$sal_price,
                 'available_quantity' => rand(0, 50),
                'status'        => rand(0, 1),
                'is_popular'  => rand(0, 1),
                'is_trending'  => rand(0, 1),
                'user_id'       => $user->random(),
                'category_id'   => $categories->random(),
                'created_at'    => $product_date,
                'updated_at'    => $product_date,

            ];
        }

        $chunks = array_chunk($product, 500);
        foreach ($chunks as $chunk) {
            Product::insert($chunk);
        }
        
    }
}

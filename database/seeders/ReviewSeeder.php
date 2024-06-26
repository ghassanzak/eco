<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker      = Factory::create();
        $review     = [];
        $users      = collect(User::get()->modelKeys());
        $product    = collect(Product::all());

        for($i = 0 ; $i < 2000; $i++) {

            // $selected_product = $product->random();
            // $product_date = $selected_product->created_at->timestamp;
            // $current_date = Carbon::now()->timestamp;

            $review[] = [
                'user_id' => rand(1, 3),
                'product_id' =>rand(1, 250),
                'product_review_details' => $faker->paragraph(2, true),
                'ip_address' => $faker->ipv4,
                'status' => rand(0, 1),
                
            ];

        }
        
        $chunks = array_chunk($review, 500);
        foreach ($chunks as $chunk) {
            ProductReview::insert($chunk);
        }
    }
}

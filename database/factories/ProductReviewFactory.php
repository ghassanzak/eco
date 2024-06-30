<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReview>
 */
class ProductReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = collect(Product::all()->modelKeys());
        $user = collect(User::where('id', '>', 1)->get()->modelKeys());
        if ($product && $user) {
            return [
                'user_id' =>$user->random(),
                'product_id' =>$product->random(),
                'product_review_details' => fake()->paragraph(2, true),
                'ip_address' => fake()->ipv4,
                'status' => rand(0, 1),
            ];
           
        }
        return [];
    }
}

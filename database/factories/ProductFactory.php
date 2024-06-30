<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = collect(Category::all()->modelKeys());
        $user = collect(User::where('id', '>', 1)->get()->modelKeys());
        return [
            'name'                  => fake()->lexify('category-?????'),
            'slug'                  =>  fake()->slug(),
            'description'           => fake()->paragraph(),
            'current_purchase_cost' =>fake()->randomFloat(1, 20, 30),
            'current_sale_price'    =>fake()->randomFloat(2, 20, 200),
            'available_quantity'    => rand(0, 50),
            'status'                => rand(0, 1),
            'is_popular'            => rand(0, 1),
            'is_trending'           => rand(0, 1),
            'user_id'               => $user->random(),
            'category_id'           => $categories->random(),
        ];
    }
}

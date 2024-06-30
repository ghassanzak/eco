<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        User::factory()->count(10)->create();

        $this->call(CategoriseSeeder::class);
        Category::factory()->count(16)->create();

        Product::factory()->count(300)->create();

        $this->call(tagsSeeder::class);

        ProductReview::factory()->count(2000)->create();
    }
}

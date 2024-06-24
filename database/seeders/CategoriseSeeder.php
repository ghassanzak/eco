<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([

            'name'=>'category1',
            'is_popular'=>1,
            'status'=>1,
        ]);
        Category::create([

            'name'=>'category2',
            'is_popular'=>'1',
            'status'=>'1',
        ]);
        Category::create([

            'name'=>'category3',
            'is_popular'=>'1',
            'status'=>'1',
        ]);
        Category::create([

            'name'=>'category4',
            'is_popular'=>'0',
            'status'=>'0',
        ]);
    }
}

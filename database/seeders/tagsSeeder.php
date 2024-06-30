<?php

namespace Database\Seeders;

use App\Models\ProductTag;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Termwind\render;

class tagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create(['name' => 'Flowers']);
        Tag::create(['name' => 'Nature']);
        Tag::create(['name' => 'Electronic']);
        Tag::create(['name' => 'Life']);
        Tag::create(['name' => 'Style']);
        Tag::create(['name' => 'Food']);
        Tag::create(['name' => 'Travel']);

        Tag::factory()->count(10)->create();


        for ($i=1; $i <= 250; $i++) { 
            for ($j=1; $j <= rand(1,5); $j++) { 
                $product_tag[] =
                [
                    'product_id' => $i,
                    'tag_id' => $j,
                ];
            }
        }

        $chunks = array_chunk($product_tag, 500);
        foreach ($chunks as $chunk) {
            ProductTag::insert($chunk);
        }
    }
}

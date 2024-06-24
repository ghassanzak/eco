<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyInfo::create([
            
            'name'              =>  'Ghassan',
            'email'             =>  'ghassan@gmail.com',
            'phone'             =>  '+963932516412',
            'company_logo'      =>  'ghassan@gmail.com',
            'facebook_link'     =>  'ghassan@gmail.com',
            'youtube_link'      =>  'ghassan@gmail.com',
            'twitter_link'      =>  'ghassan@gmail.com',
            'company_address'   =>  'gh',
            'about_us'          =>  'gh',
            'refund_policy'     =>  'gh',
            'privacy_policy'    =>  'gh',
            'shipping_policy'   =>  'gh',
            'terms_condition'   =>  'gh',

        ]);
    }
}

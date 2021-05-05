<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker::create();

        $categories = ['Fashion', 'Electricals','Telecom', 'Hardware','Travel'];
        foreach($categories as $cat){
            $category = new Category();
            $category->name = $cat;
            $category->save();
        }
        
    }
}

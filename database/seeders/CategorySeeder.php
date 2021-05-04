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

        $categories = ['fashion', 'electricals','telecom', 'hardware','travel'];
        foreach($categories as $cat){
            $category = new Category();
            $category->name = $cat;
            $category->save();
        }
        
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory;
use Faker\Factory as Faker;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $categorytickets = [
            ['name'=>'Missing cashback','description'=>'You made a purchase more than 1 days ago but its not showing in your account'],
            ['name'=>'Declined cashback','description'=>'Your cashback was declined'],
            ['name'=>'Incorrect amount','description'=>'Your cashback is at tracked, confirmed or paid status but its the wrong amount'],
            ['name'=> 'Contact Us','description'=>'You Will Get Response In 24 hours'],
         ];
        foreach($categorytickets as $tickets){
            $ticket = TicketCategory::create($tickets);
        }
    }
}

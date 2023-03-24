<?php

namespace Database\Seeders;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\tags.csv');
        $tags = [];
        $now = Carbon::now();
        foreach ($csvToArray as $tag) {
            $tags[] = [
                'title' => $tag['title'],
                'type' => $tag['type'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Tag::insert($tags);
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all()->pluck('id')->toArray();
        $positions = ['CEO', 'HR', 'CFO', 'Vice-president'];
        return [
            'title' => $this->faker->title,
            'user_id' => Arr::random($users),
            'description' => $this->faker->text(),
            'image' => 'testimonial1671626387.jpg',
            'name' => $this->faker->name,
            'position' => Arr::random($positions),
            'company' => 'Cashback',
            'status' => 'active',
            'url' => null,
            'order_no' => mt_rand(1,10)
        ];
    }
}

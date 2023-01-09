<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    private $count = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super admin
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@trs.com',
            'password' => bcrypt('123@#$xyz990'),
            'registration_type' => 'sign up',
            'avatar' => 'default.png',
            'is_email_verified' => 1,
        ])->assignRole(['admin', 'user']);

        // Data Operator
        User::create([
            'first_name' => 'Data',
            'last_name' => 'Operator',
            'email' => 'data@trs.com',
            'password' => bcrypt('123@#$xyz990'),
            'registration_type' => 'sign up',
            'avatar' => 'default.png',
            'is_email_verified' => 1,
        ])->assignRole(['data']);

        // Finance Manager
        User::create([
            'first_name' => 'Finance',
            'last_name' => 'Manager',
            'email' => 'finance@trs.com',
            'password' => bcrypt('123@#$xyz990'),
            'registration_type' => 'sign up',
            'avatar' => 'default.png',
            'is_email_verified' => 1,
        ])->assignRole(['finance']);

        $faker = Faker::create();

        $users = [];
        $password = bcrypt('123@#$xyz990'); // important optimization

        for ($i = 0; $i < $this->count; $i++) {
            $users[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => $password,
                'registration_type' => 'sign up',
                'avatar' => 'default.png',
                'is_email_verified' => 1,
            ];
        }

        foreach (array_chunk($users, 500) as $usersChunk) {
            User::insert($usersChunk);
        }

        Role::findByName('user')->users()->sync(User::whereNotIn('id', [1, 2, 3])->pluck('id'));
    }
}

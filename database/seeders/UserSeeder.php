<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;

use App\Models\User;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create(['name' => 'admin']);
		$user_role = Role::create(['name' => 'user']);
		
		$admin = new User();
		$admin->first_name = 'admin';
		$admin->last_name = 'admin';
		$admin->email = 'admin@trs.com';
		$admin->password = bcrypt('123@#$xyz990');
		$admin->registration_type = 'sign up';
		$admin->save();
        $admin->assignRole($admin_role);

        $faker = Faker::create();

    	foreach (range(1,50) as $index) {

            $user = new User();
            $user->first_name = $faker->firstName;
            $user->last_name = $faker->lastName;
            $user->email = $faker->email;
            $user->password = bcrypt('123@#$xyz990');
            $user->registration_type = 'sign up';
            $user->save();	     
            $user->assignRole($user_role);
	}

    }
}

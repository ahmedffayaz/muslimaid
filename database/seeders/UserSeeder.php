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
        $admin = new User();
		$admin->first_name = 'Super';
		$admin->last_name = 'Admin';
		$admin->email = 'admin@trs.com';
		$admin->password = bcrypt('123@#$xyz990');
		$admin->registration_type = 'sign up';
        $admin->avatar = 'default.png';
		$admin->save();
        $admin->assignRole('admin');
        $admin->assignRole('user');

        $data = new User();
		$data->first_name = 'Data';
		$data->last_name = 'Operator';
		$data->email = 'data@trs.com';
		$data->password = bcrypt('123@#$xyz990');
		$data->registration_type = 'sign up';
        $data->avatar = 'default.png';
		$data->save();
        $data->assignRole('data');

        $finance = new User();
		$finance->first_name = 'Finance';
		$finance->last_name = 'Manager';
		$finance->email = 'finance@trs.com';
		$finance->password = bcrypt('123@#$xyz990');
		$finance->registration_type = 'sign up';
        $finance->avatar = 'default.png';
		$finance->save();
        $finance->assignRole('finance');

        $faker = Faker::create();

    	foreach (range(1,1000) as $index) {

            $user = new User();
            $user->first_name = $faker->firstName;
            $user->last_name = $faker->lastName;
            $user->email = $faker->unique()->email;
            $user->password = bcrypt('123@#$xyz990');
            $user->registration_type = 'sign up';
            $user->avatar = 'default.png';
            $user->save();	     
            $user->assignRole('user');
	}

    }
}

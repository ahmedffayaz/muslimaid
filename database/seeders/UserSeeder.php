<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\users.csv');
        $users = [];
        $roles = [];
        $password = bcrypt('123@#$xyz990'); // important optimization
        $now = Carbon::parse(now())->format('Y-m-d H:i:s');

        foreach ($csvToArray as $row) {
            foreach (explode(',', $row['roles']) as $role) {
                $roles[trim($role)][] = $row['id'];
            }

            $users[] = [
                'id' => $row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'password' => $password,
                'registration_type' => 'sign up',
                'registration_type' => 'sign up',
                'avatar' => 'default.png',
                'status' => $row['status'],
                'is_email_verified' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($users, 500) as $usersChunk) {
            User::insert($usersChunk);
        }

        foreach ($roles as $roleName => $roleIds) {
            Role::findByName($roleName)->users()->sync($roleIds);
        }
    }
}

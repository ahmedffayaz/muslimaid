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
                'email_verified_at' =>  isset($row['email_verified_at']) ? ($row['email_verified_at'] == 'Yes' ? $row['updated_at'] : null) : null, 
                'password' => $password,
                'registration_type' => 'sign up',
                'intro' => isset($row['intro']) ? $row['intro'] : null,
                'address' =>  isset($row['address']) ? $row['address'] : null,
                'phone' =>  isset($row['phone']) ? $row['phone'] : null,
                'avatar' => isset($row['avatar']) ? $row['avatar'] : 'default.png',
                'status' => (isset($row['status']) ? isset($row['status']): 1),
                'referred_by' => isset($row['referred_by']) ? $row['referred_by'] : null,
                'referred_at' => isset($row['referred_at']) ? $row['referred_at'] : null,
                'remember_token' => isset($row['remember_token']) ? $row['remember_token'] : null,
                'created_at' => isset($row['created_at']) ? $row['created_at'] : $now,
                'updated_at' => isset($row['updated_at']) ? $row['updated_at'] : $now,
                'deleted_at' => null,
                'provider' => isset($row['provider']) ? $row['provider'] : 'email',
                'provider_id' => isset($row['provider_id']) ? $row['provider_id'] : null,
                'is_email_verified' => isset($row['is_email_verified']) ? $row['is_email_verified'] : 1,
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

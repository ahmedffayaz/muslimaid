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
            if (
                !arrayValueExists($row, 'id')
                || !arrayValueExists($row, 'first_name')
                || !arrayValueExists($row, 'last_name')
                || !arrayValueExists($row, 'email')
                || !arrayValueExists($row, 'roles')
            ) {
                continue;
            }

            foreach (explode(',', $row['roles']) as $role) {
                $roles[trim($role)][] = $row['id'];
            }

            $users[] = [
                'id' => $row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'email_verified_at' => isset($row['email_verified_at']) && $row['email_verified_at'] == 'Yes' ? $now : null,
                'password' => $password,
                'registration_type' => 'sign up',
                'date_of_birth' => arrayValueExists($row, 'date_of_birth') ? date("Y-m-d", strtotime($row['date_of_birth'])):  null,
                'intro' => isset($row['intro']) ? $row['intro'] : null,
                'address' =>  isset($row['address']) ? $row['address'] : null,
                'phone' =>  isset($row['phone']) ? $row['phone'] : null,
                'avatar' => arrayValueExists($row, 'avatar') ? $row['avatar'] :  'default.png',
                'status' => arrayValueExists($row, 'status') ? $row['status'] :  'active',
                'referred_by' => isset($row['referred_by']) ? $row['referred_by'] : null,
                'referred_at' => isset($row['referred_at']) ? $row['referred_at'] : null,
                'remember_token' => isset($row['remember_token']) ? $row['remember_token'] : null,
                'created_at' => arrayValueExists($row, 'created_at') ? dbDate($row['created_at']) : $now,
                'updated_at' => arrayValueExists($row, 'updated_at') ? dbDate($row['updated_at']) : $now,
                'deleted_at' => null,
                'provider' => isset($row['provider']) ? $row['provider'] : 'email',
                'provider_id' => isset($row['provider_id']) ? $row['provider_id'] : null,
                'is_email_verified' => arrayValueExists($row, 'is_email_verified') ? $row['is_email_verified'] :  1,
                'meta_data' => [
                    'referral_code' => isset($row['ref_code']) ? $row['ref_code'] : null,
                ]
            ];
        }

        foreach (array_chunk($users, 500) as $usersChunk) {
            if (empty($usersChunk)) return;
            foreach ($usersChunk as $userData) {
                if (empty($userData)) return;
                $user = User::create([
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                    'email_verified_at' => $userData['email_verified_at'],
                    'password' => $userData['password'],
                    'registration_type' => $userData['registration_type'],
                    'date_of_birth' => $userData['date_of_birth'],
                    'intro' => $userData['intro'],
                    'address' => $userData['address'],
                    'phone' => $userData['phone'],
                    'avatar' => $userData['avatar'],
                    'status' => $userData['status'],
                    'referred_by' => $userData['referred_by'],
                    'referred_at' => $userData['referred_at'],
                    'remember_token' => $userData['remember_token'],
                    'created_at' => $userData['created_at'],
                    'updated_at' => $userData['updated_at'],
                    'deleted_at' => $userData['deleted_at'],
                    'provider' => $userData['provider'],
                    'provider_id' => $userData['provider_id'],
                    'is_email_verified' => $userData['is_email_verified'],
                ]);

                // Insert user metadata if it exists
                if (isset($userData['meta_data']) && is_array($userData['meta_data'])) {
                    foreach ($userData['meta_data'] as $key => $metaData) {
                        if (!empty($metaData)) {
                            $user->metaData()->create([
                                'user_id' => $user->id,
                                'type' => $key,
                                'value' => $metaData,
                            ]);
                        }
                    }
                }
            }
        }

        foreach ($roles as $roleName => $roleIds) {
            Role::findByName($roleName)->users()->sync($roleIds);
        }
    }
}

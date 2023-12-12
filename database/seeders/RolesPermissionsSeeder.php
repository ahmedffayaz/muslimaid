<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('optimize:clear');
        Schema::disableForeignKeyConstraints();
		DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
		Schema::enableForeignKeyConstraints();

        $permissions = [
            'store_permissions'         => ['view stores', 'edit stores', 'delete stores', 'add stores'],
            'cashback_permissions'      => ['view cashback', 'edit cashback', 'delete cashback', 'add cashback'],
            'user_permissions'          => ['view users', 'edit users', 'delete users', 'add users', 'change password', 'edit payment info'],
            'categories_permissions'    => ['view categories', 'edit categories', 'delete categories', 'add categories'],
            'reviews_permissions'       => ['view reviews', 'edit reviews', 'delete reviews', 'add  reviews'],
            'vouchers_permissions'      => ['view vouchers', 'edit vouchers', 'delete vouchers', 'add vouchers'],
            'reports_permissions'       => ['view clicks', 'view performance', 'view earnings'],
            'settings_permissions'      => ['view settings', 'edit settings'],
            'tickets_permissions'       => ['view tickets', 'reply tickets', 'close tickets'],
            'network_permissions'       => ['view networks', 'run importer', 'view network categories', 'map categories'],
            'languages_permissions'     => ['view languages', 'add languages', 'delete languages', 'edit languages'],
            'translations_permissions'  => ['view translations', 'add translations', 'delete translations', 'edit translations'],
        ];

        // Define additional permissions based on conditions
        $additionalPermissions = [];

        if (getImporterYMLSettings(config('app.appeals_yaml_path'))) {
            $additionalPermissions['appeals_permissions'] = ['view appeals', 'edit appeals', 'delete appeals', 'add appeals'];
        }

        if (getImporterYMLSettings(config('app.banners_yaml_path'))) {
            $additionalPermissions['banners_permissions'] = ['view banners', 'edit banners', 'delete banners', 'add banners'];
        }

        // Merge $additionalPermissions into $permissions
        $permissions = array_merge_recursive($permissions, $additionalPermissions);

        foreach ($permissions as $items) {
            foreach ($items as $permission) {
                Permission::create(['name' => $permission]);
            }
        }

        $admin_role   = Role::create(['name' => 'admin']);
        $user_role    = Role::create(['name' => 'user']);
        $data_role    = Role::create(['name' => 'data']);
        $finance_role = Role::create(['name' => 'finance']);

        $data_role->givePermissionTo(
            $permissions['store_permissions'],
            $permissions['user_permissions'],
            $permissions['categories_permissions'],
            $permissions['vouchers_permissions'],
            $permissions['reviews_permissions'],
            $permissions['languages_permissions'],
            $permissions['translations_permissions'],
            $permissions['network_permissions']
        );


        $finance_role->givePermissionTO(
            $permissions['reports_permissions'],
            $permissions['cashback_permissions'],
            $permissions['store_permissions'],
            $permissions['user_permissions'],
            $permissions['categories_permissions'],
            $permissions['vouchers_permissions'],
            $permissions['reviews_permissions'],
            $permissions['languages_permissions'],
            $permissions['translations_permissions'],
            $permissions['network_permissions']
        );
    }
}

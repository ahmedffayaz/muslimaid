<?php

namespace Database\Seeders;

use App\Models\UserCashback;
use Carbon\Carbon;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserCashbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('charities')->truncate();
        Schema::enableForeignKeyConstraints();

        $storeIdData = Store::pluck('id')->toArray();
        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\user_cashbacks.csv');
        $userCashbacks = [];
        $now = Carbon::now();
        if (isset($csvToArray[0])) {
            foreach ($csvToArray as $userCashback) {
                !isset($userCashback['id']) ?  ($userCashback['id'] = reset($userCashback)) : '';
                if (
                    !arrayValueExists($userCashback, 'id')
                    || !arrayValueExists($userCashback, 'user_id')
                    || !in_array($userCashback['store_id'], $storeIdData)
                ) {
                    continue;
                }
                $userCashbacks[] = [
                    'id' => $userCashback['id'],
                    'store_id' => (isset($userCashback['store_id']) && $userCashback['store_id'] != '') ? $userCashback['store_id'] : null,
                    'user_id' => $userCashback['user_id'],
                    'cashout_id' => arrayValueExists($userCashback, 'cashout_id') ? $userCashback['cashout_id'] : null,
                    'exit_click_id' =>  arrayValueExists($userCashback, 'exit_click_id') ? $userCashback['exit_click_id'] : null,
                    'network_order_id' => arrayValueExists($userCashback, 'network_order_id') ? $userCashback['network_order_id'] : null,
                    'network_commission_id' => arrayValueExists($userCashback, 'network_commission_id') ? $userCashback['network_commission_id'] : null,
                    'details' =>  arrayValueExists($userCashback, 'exit_click_id') ? $userCashback['details'] : null,
                    'click_date' => arrayValueExists($userCashback, 'click_date') ? $userCashback['click_date'] : null,
                    'event_date' =>  arrayValueExists($userCashback, 'event_date') ? $userCashback['event_date'] : null,
                    'network_commission' => arrayValueExists($userCashback, 'network_commission') ? $userCashback['network_commission'] : null,
                    'order_value' => arrayValueExists($userCashback, 'order_value') ? $userCashback['order_value'] : null,
                    'amount' =>  arrayValueExists($userCashback, 'amount') ? $userCashback['amount'] : 0,
                    'status' => arrayValueExists($userCashback, 'status') ? $userCashback['status'] : null,
                    'type' => arrayValueExists($userCashback, 'type') ? $userCashback['type'] : null,
                    'created_at' =>  arrayValueExists($userCashback, 'created_at') ? dbDate($userCashback['created_at']) : $now,
                    'updated_at' =>  arrayValueExists($userCashback, 'updated_at') ? dbDate($userCashback['updated_at']) : $now,
                ];
            }
            foreach (array_chunk($userCashbacks, 500) as $userCashbacksChunk) {
                UserCashback::insert($userCashbacksChunk);
            }
        }
    }
}

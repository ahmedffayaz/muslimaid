<?php

namespace Database\Seeders;

use App\Models\ExitClick;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExitClickSeeder extends Seeder
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

        $csvToArray = csvToArray('resources\\views\\frontend\\seeders\\exit_clicks.csv');
        $exitClicks = [];
        $now = Carbon::now();
        if (isset($csvToArray[0])) {
            foreach ($csvToArray as $exitClick) {
                !isset($exitClick['id']) ?  ($exitClick['id'] = reset($exitClick)) : '';
                $store_data = Store::where('id',$exitClick['store_id'])->first();
                if (
                    !arrayValueExists($exitClick, 'id')
                    || !arrayValueExists($exitClick, 'store_id')
                    || !arrayValueExists($exitClick, 'user_id')
                    || !isset($store_data)
                ) {
                    continue;
                }
                $exitClicks[] = [
                    'id' => $exitClick['id'],
                    'store_id' => $exitClick['store_id'],
                    'user_id' => $exitClick['user_id'],
                    'network_id' => arrayValueExists($exitClick, 'network_id') ? $exitClick['network_id'] : null,
                    'conversion' => isset($exitClick['conversion']) && $exitClick['conversion'] == 'No' ? 0 : 1,
                    'current_cashback_percentage' => arrayValueExists($exitClick, 'commission_percentage') ? $exitClick['commission_percentage'] : null,
                    'created_at' =>  arrayValueExists($exitClick, 'created_at') ? dbDate($exitClick['created_at']) : $now,
                    'updated_at' =>  arrayValueExists($exitClick, 'updated_at') ? dbDate($exitClick['updated_at']) : $now,
                ];
            }
            foreach (array_chunk($exitClicks, 500) as $exitClicksChunk) {
                ExitClick::insert($exitClicksChunk);
            }
        }
    }
}

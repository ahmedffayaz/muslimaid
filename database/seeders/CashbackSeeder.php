<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserCashback;
use App\Models\CashbackStatusChange;
use App\Models\ExitClick;
use App\Models\SiteSetting;
use Faker\Factory as Faker;

class CashbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker::create();
        $cashback_percent = SiteSetting::where('type','cashback_percentage')->first()->value;

        foreach (range(1,1000) as $index) {

            
            $order_value = $faker->numberBetween(50,200);
            $network_commission = $faker->numberBetween(1,10);
            $amount = ($network_commission/100) * $cashback_percent;

            $click = ExitClick::findOrFail($faker->numberBetween(1,5000));
            $commission = UserCashback::create([
            'store_id' => $click->store_id,
            'user_id'  => $click->user_id ?? 0,
            'exit_click_id' => $click->id,
            'amount' => round($amount,3),
            'network_commission' => round($network_commission,3),
            'order_value' => round($order_value,3),
            'status' => $faker->numberBetween(1,4),
            'event_date'=> \Carbon\Carbon::parse($click->created_at)->format('Y-m-d H:i:s'),
            'click_date'=> $click->created_at,
            
        ]); 

        $change_status = CashbackStatusChange::create([
            'user_cashback_id'=>$commission->id,
            'cashback_status_id'=>$commission->status

        ]);
        }
    }
}

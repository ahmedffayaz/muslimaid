<?php

namespace Database\Seeders;

use App\Models\ExitClick;
use App\Models\SiteSetting;
use Faker\Factory as Faker;
use App\Models\UserCashback;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CashbackStatusChange;

class CashbackSeeder extends Seeder
{
    private $count = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $now = Carbon::parse(now())->format('Y-m-d H:i:s');

        $cashbacks = [];
        $cashbacksStatuses = [];

        $clicks = ExitClick::orderBy(DB::raw('RAND()'))->limit($this->count)->get()->toArray();
        $cashbackPercent = SiteSetting::where('type', 'cashback_percentage')->first()->value;

        for ($i = 0; $i < $this->count; $i++) {
            $orderValue = $faker->numberBetween(50, 200);
            $networkCommission = $faker->numberBetween(1, 10);
            $amount = ($networkCommission / 100) * $cashbackPercent;

            $status = $faker->numberBetween(1, 4);

            $cashbacks[] = [
                'store_id' => $clicks[$i]['store_id'],
                'user_id'  => $clicks[$i]['user_id'] ?? 0,
                'exit_click_id' => $clicks[$i]['id'],
                'amount' => round($amount, 3),
                'network_commission' => round($networkCommission, 3),
                'order_value' => round($orderValue, 3),
                'status' => $status,
                'event_date' => $now,
                'click_date' => $now,
            ];

            $cashbacksStatuses[] = [
                'user_cashback_id' => ($i + 1),
                'cashback_status_id' => $status
            ];
        }

        foreach (array_chunk($cashbacks, 500) as $cashbacksChunk) {
            UserCashback::insert($cashbacksChunk);
        }

        foreach (array_chunk($cashbacksStatuses, 500) as $cashbacksStatusesChunk) {
            CashbackStatusChange::insert($cashbacksStatusesChunk);
        }
    }
}

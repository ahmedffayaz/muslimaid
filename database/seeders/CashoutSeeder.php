<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Cashout;

class CashoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $cashouts = [
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),
            array(
                'user_id'=>$faker->numberBetween(1,50),
                'amount'=>$faker->numberBetween(10,500),
                'cashout_type'=>null,
                'paypal_email'=>$faker->email,
                'address'=>null,
                'city'=>null,
                'postcode'=>null,
                'country'=>null,
                'account_name'=>null,
                'bank_title'=>null,
                'account_number'=>null,
                'bank_sort_code'=>null,
                'new_cashout' => '0',
                'bic'=>null,
                'payment_method'=>'paypal', 
                'status'=>'paid',
            ),

        ];

        foreach($cashouts as $cash){
           $cashbk = Cashout::create($cash);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromCashouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashouts', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('cashout_type');
            $table->dropColumn('paypal_email');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('postcode');
            $table->dropColumn('country');
            $table->dropColumn('account_name');
            $table->dropColumn('bank_title');
            $table->dropColumn('account_number');
            $table->dropColumn('bank_sort_code');
            $table->dropColumn('bic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashouts', function (Blueprint $table) {
            $table->string('cashout_type')->nullable()->after('amount');
            $table->string('first_name')->nullable()->after('payment_method');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('paypal_email')->nullable()->after('last_name');
            $table->string('address')->nullable()->after('paypal_email');
            $table->string('city')->nullable()->after('address');
            $table->string('postcode')->nullable()->after('city');
            $table->string('country')->nullable()->after('postcode');
            $table->string('account_name')->nullable()->after('country');
            $table->string('bank_title')->nullable()->after('account_name');
            $table->string('account_number')->nullable()->after('bank_title');
            $table->string('bank_sort_code')->nullable()->after('account_number');
            $table->string('bic')->nullable()->after('bank_sort_code');
        });
    }
}

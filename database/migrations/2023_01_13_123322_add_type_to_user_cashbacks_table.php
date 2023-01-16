<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToUserCashbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cashbacks', function (Blueprint $table) {
            $table->enum('type', ['cashback', 'welcome_bonus', 'referral_bonus'])->default('cashback')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cashbacks', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

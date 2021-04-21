<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserCashbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cashbacks', function (Blueprint $table) {
            $table->longText('details')->after('exit_click_id')->nullable();
            $table->float('network_commission')->after('event_date')->nullable();
            $table->float('order_value')->after('event_date')->nullable();

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
            $table->dropColumn('details');
            $table->dropColumn('network_commission');
            $table->dropColumn('order_value');
        });
    }
}

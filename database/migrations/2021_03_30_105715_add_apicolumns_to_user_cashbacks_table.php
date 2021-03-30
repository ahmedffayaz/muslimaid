<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApicolumnsToUserCashbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cashbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('exit_click_id')->nullable()->after('user_id');
            $table->dateTime('click_date')->nullable()->after('status');
            $table->dateTime('event_date')->nullable()->after('status');

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
            $table->dropColumn('exit_click_id');
            $table->dropColumn('click_date');
            $table->dropColumn('event_date');

        });
    }
}

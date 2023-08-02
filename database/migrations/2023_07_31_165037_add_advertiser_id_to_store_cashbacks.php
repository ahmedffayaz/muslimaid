<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvertiserIdToStoreCashbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_cashbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('advertiser_id')->nullable()->after('network_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_cashbacks', function (Blueprint $table) {
            $table->dropColumn('advertiser_id');
        });
    }
}

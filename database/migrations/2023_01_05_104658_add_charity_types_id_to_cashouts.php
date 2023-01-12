<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharityTypesIdToCashouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashouts', function (Blueprint $table) {
            $table->unsignedBigInteger('charity_types_id')->nullable()->after('new_cashout');
            
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
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCashbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cashbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exit_click_id')->nullable();
            $table->unsignedBigInteger('network_order_id')->nullable();
            $table->unsignedBigInteger('network_commission_id')->nullable();
            $table->longText('details')->nullable();
            $table->dateTime('click_date')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->float('network_commission')->nullable();
            $table->float('order_value')->nullable();
            $table->string('amount');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_cashbacks');
    }
}

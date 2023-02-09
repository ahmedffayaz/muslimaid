<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCashbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_cashbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->string('type')->nullable();
            $table->string('cashback_name')->nullable();
            $table->string('image')->nullable();
            $table->text('click_url')->nullable();
            $table->text('deeplink_url')->nullable();
            $table->text('sale_commission')->nullable();
            $table->text('currency')->nullable();
            $table->longText('detail')->nullable();
            $table->longText('network_detail')->nullable();
            $table->string('default')->default(0)->nullable();
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
        Schema::dropIfExists('store_cashbacks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_clicks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('network_id')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('network_click_ref')->nullable();
            $table->longText('exit_url')->nullable();
            $table->double('current_cashback_percentage')->nullable();
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
        Schema::dropIfExists('exit_clicks');
    }
}

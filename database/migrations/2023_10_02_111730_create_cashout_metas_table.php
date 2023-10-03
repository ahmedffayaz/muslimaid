<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashoutMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashout_metas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cashout_id');
            $table->foreign('cashout_id')->references('id')->on('cashouts')->onDelete('cascade');

            $table->string('type');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashout_metas');
    }
}

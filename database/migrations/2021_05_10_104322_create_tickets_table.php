<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('click_id')->unsigned()->nullable();
            $table->integer('cashback_id')->unsigned()->nullable();
            $table->string('ticket_id')->unique()->nullable();
            $table->string('title');
            $table->string('ticket_type')->nullable();
            $table->string('claim_type')->nullable();
            $table->string('priority')->nullable();
            $table->string('claim_amount')->nullable();
            $table->text('message')->nullable();
            $table->string('new_ticket')->default(1);
            $table->dateTime('closing_time')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();  
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
        Schema::dropIfExists('tickets');
    }
}

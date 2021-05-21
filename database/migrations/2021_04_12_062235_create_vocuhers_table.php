<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVocuhersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->longText('description')->nullable();
            $table->text('destination')->nullable();
            $table->string('link_id')->nullable();
            $table->string('link_name')->nullable();
            $table->string('link_type')->nullable();
            $table->string('promotion_type')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('image')->nullable();
            $table->text('click_url')->nullable();
            $table->text('sale_commission')->nullable();
            $table->dateTime('promotion_end_date')->nullable();
            $table->dateTime('promotion_start_date')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}

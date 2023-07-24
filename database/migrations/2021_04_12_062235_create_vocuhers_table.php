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
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->text('tracking_url')->nullable();
            $table->text('deeplink_url')->nullable();
            $table->enum('promotion_type', ['Coupon', 'Sale/Discount']);
            $table->string('coupon_code')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('promotion_start_date')->nullable();
            $table->dateTime('promotion_end_date')->nullable();
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

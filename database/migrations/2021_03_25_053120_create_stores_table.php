<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('network_id');
            $table->unsignedBigInteger('advertiser_id')->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('slug')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->longText('extra_info')->nullable();
            $table->longText('tracking_url');
            $table->string('store_url')->nullable();
            $table->string('network_status')->nullable();
            $table->string('status_description')->nullable();
            $table->string('override_cashback')->default(0);
            $table->string('override_categories')->default(0);
            $table->string('status')->default(1);
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
        Schema::dropIfExists('stores');
    }
}

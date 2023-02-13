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
            $table->longText('deeplink_url')->nullable();
            $table->longText('store_url')->nullable();
            $table->string('network_status')->nullable();
            $table->string('status_description')->nullable();
            $table->string('override_cashback')->default(0);
            $table->string('override_categories')->default(0);
            $table->string('feature_homepage')->default(0);
            $table->string('feature_sidebar')->default(0);
            $table->string('editor_pick')->default(0);
            $table->string('custom_cashback_percentage')->nullable();
            $table->string('status')->default(1);
            $table->string('is_fake')->default(0);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10,8)->nullable();
            $table->decimal('longitude', 10,8)->nullable();
            $table->integer('rating')->default(0);
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

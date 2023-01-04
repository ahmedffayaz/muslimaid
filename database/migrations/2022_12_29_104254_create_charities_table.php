<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('country');
            $table->unsignedBigInteger('charity_types_id');
            $table->string('logo_type')->nullable();
            $table->string('logo_upload')->nullable();
            $table->string('logo_link')->nullable();
            $table->string('banner_type')->nullable();
            $table->string('banner_upload')->nullable();
            $table->string('banner_link')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('charities');
    }
}

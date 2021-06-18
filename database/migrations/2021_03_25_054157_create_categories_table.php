<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('slug');
            $table->longText('description')->nullable();
            $table->string('sort')->nullable();
            $table->string('logo_type')->nullable();
            $table->string('logo_upload')->nullable();
            $table->string('logo_link')->nullable();
            $table->string('banner_type')->nullable();
            $table->string('banner_upload')->nullable();
            $table->string('banner_link')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('categories');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('slug');
            $table->text('excerpt')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->longText('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('banner_image')->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['system', 'special', 'general'])->default('general');
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
        Schema::dropIfExists('pages');
    }
}

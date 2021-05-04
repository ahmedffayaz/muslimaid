<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->longText('description')->after('name')->nullable();
            $table->string('sort')->after('description')->nullable();
            $table->string('logo_type')->after('sort')->nullable();
            $table->string('logo_upload')->after('logo_type')->nullable();
            $table->string('logo_link')->after('logo_upload')->nullable();
            $table->string('banner_type')->after('logo_link')->nullable();
            $table->string('banner_upload')->after('banner_type')->nullable();
            $table->string('banner_link')->after('banner_upload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('sort');
            $table->dropColumn('logo_type');
            $table->dropColumn('logo_upload');
            $table->dropColumn('logo_link');
            $table->dropColumn('banner_type');
            $table->dropColumn('banner_upload');
            $table->dropColumn('banner_link');
            
        });
    }
}

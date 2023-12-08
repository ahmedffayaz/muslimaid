<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworkIdToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('network_id')->after('parent_id')->nullable();
            $table->foreign('network_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('advertiser_id')->after('network_id')->nullable();
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
            // Drop foreign key constraints before dropping columns
            $table->dropForeign(['network_id']);

            // Drop columns
            $table->dropColumn('network_id');
            $table->dropColumn('advertiser_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToStoreReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('store_id');
            $table->dropColumn('reviewer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_reviews', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('reviewer');
        });
    }
}

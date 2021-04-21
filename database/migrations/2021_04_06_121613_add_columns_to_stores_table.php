<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->longText('description')->after('name')->nullable();
            $table->string('slug')->after('name')->nullable();
            $table->longText('terms_conditions')->after('store_url')->nullable();
            $table->longText('extra_info')->after('store_url')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('slug');
            $table->dropColumn('terms_conditions');
            $table->dropColumn('extra_info');
        });
    }
}

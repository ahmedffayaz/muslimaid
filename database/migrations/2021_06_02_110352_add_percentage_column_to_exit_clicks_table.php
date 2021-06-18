<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageColumnToExitClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exit_clicks', function (Blueprint $table) {
            $table->string('current_cashback_percentage')->nullable()->after('exit_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exit_clicks', function (Blueprint $table) {
            $table->dropColumn('current_cashback_percentage');
        });
    }
}

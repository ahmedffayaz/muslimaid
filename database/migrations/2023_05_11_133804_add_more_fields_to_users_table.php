<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address_2')->nullable()->after('address');
            $table->string('street')->nullable()->after('address_2');
            $table->unsignedBigInteger('country_id')->nullable()->after('street');
            $table->string('postal_code')->nullable()->after('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address_2');
            $table->dropColumn('street');
            $table->dropColumn('country_id');
            $table->dropColumn('postal_code');
        });
    }
}

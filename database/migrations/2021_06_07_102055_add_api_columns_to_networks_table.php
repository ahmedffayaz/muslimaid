<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiColumnsToNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('networks', function (Blueprint $table) {
           $table->string('token')->nullable()->after('click_ref');
           $table->string('website_id')->nullable()->after('click_ref');
           $table->string('requestor_cid')->nullable()->after('click_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('website_id');
            $table->dropColumn('requestor_cid');
        });
    }
}

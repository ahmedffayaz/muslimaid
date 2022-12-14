<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSeoRuleDataMetaKeywrod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_rule_datas', function (Blueprint $table) {
            $table->dropColumn('meta_keyword');
            $table->dropColumn('meta_description');
            $table->string('type')->after('seo_rule_id');
            $table->string('key')->after('type');
            $table->string('value')->after('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_rule_datas', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('key');
            $table->dropColumn('value');
        });
    }
}

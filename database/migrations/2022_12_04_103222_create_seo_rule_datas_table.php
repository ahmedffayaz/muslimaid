<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoRuleDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_rule_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seo_rule_id')->constrained('seo_rules')->cascadeOnDelete();;
            $table->string('type');
            $table->longText('value');
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
        Schema::dropIfExists('seo_rule_datas');
    }
}

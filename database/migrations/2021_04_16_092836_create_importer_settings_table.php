<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImporterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importer_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('network_id');
            $table->integer('import_stores')->default(1);
            $table->integer('import_vouchers')->default(1);
            $table->integer('import_cashbacks')->default(1);
            $table->dateTime('last_import_at')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('importer_settings');
    }
}

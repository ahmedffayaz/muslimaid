<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportCategoriesToImporterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importer_settings', function (Blueprint $table) {
            $table->integer('import_categories')->default(1)->after('import_cashbacks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importer_settings', function (Blueprint $table) {
            $table->dropColumn('import_categories');
        });
    }
}

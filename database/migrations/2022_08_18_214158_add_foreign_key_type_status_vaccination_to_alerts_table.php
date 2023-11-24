<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyTypeStatusVaccinationToAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->unsignedBigInteger('type_status_vaccination_id')->after('genre_id')->nullable(true);
            $table->foreign('type_status_vaccination_id')->references('id')->on('type_status_vaccinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign('alerts_type_status_vaccination_id_foreign');
            $table->dropColumn('type_status_vaccination_id');
        });
    }
}

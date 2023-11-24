<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRecordIdToVaccineScheduledAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccine_scheduled_alerts', function (Blueprint $table) {
            $table->unsignedBigInteger('record_id')->nullable(true)->after('alert_id');
            $table->foreign('record_id')->references('id')->on('records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccine_scheduled_alerts', function (Blueprint $table) {
            $table->dropForeign(['record_id']);
            $table->dropColumn('record_id');
        });
    }
}

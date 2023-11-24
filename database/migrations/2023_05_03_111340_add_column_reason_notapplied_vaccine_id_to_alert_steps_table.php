<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReasonNotappliedVaccineIdToAlertStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_steps', function (Blueprint $table) {
            $table->unsignedBigInteger('reason_not_applied_vaccine_id')->nullable(true)->after('reason_close_alert_id');
            $table->foreign('reason_not_applied_vaccine_id')->references('id')->on('reason_not_applied_vaccines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alert_steps', function (Blueprint $table) {
            $table->dropForeign(['reason_not_applied_vaccine_id']);
            $table->dropColumn('reason_not_applied_vaccine_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReasonCloseAlertIdToAlertStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alert_steps', function (Blueprint $table) {
            $table->unsignedBigInteger('reason_close_alert_id')->nullable(true)->after('user_id');
            $table->foreign('reason_close_alert_id')->references('id')->on('reason_close_alerts');
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
            $table->dropForeign(['reason_close_alert_id']);
            $table->dropColumn('reason_close_alert_id');
        });
    }
}

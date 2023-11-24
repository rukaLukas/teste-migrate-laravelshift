<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnAlertIdIntoRecordIdToForwardingsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forwardings', function (Blueprint $table) {
            $table->dropForeign(['alert_id']);
            $table->dropColumn('alert_id');
            $table->unsignedBigInteger('record_id')->nullable(true)->after('user_id');
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
        Schema::table('forwardings', function (Blueprint $table) {
            $table->dropForeign(['record_id']);
            $table->dropColumn('record_id');
            $table->unsignedBigInteger('alert_id')->nullable(true)->after('user_id');
            $table->foreign('alert_id')->references('id')->on('alerts');
        });
    }
}

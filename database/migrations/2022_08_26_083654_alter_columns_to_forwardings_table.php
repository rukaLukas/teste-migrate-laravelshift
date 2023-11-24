<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToForwardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forwardings', function (Blueprint $table) {
            $table->renameColumn('name', 'description');
            $table->unsignedBigInteger('alert_id')->after('uuid')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('alert_id')->references('id')->on('alerts');
            $table->foreign('user_id')->references('id')->on('users');
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
            $table->renameColumn('description', 'name');
            $table->dropForeign('forwardings_alert_id_foreign');
            $table->dropColumn('alert_id');
            $table->dropForeign('forwardings_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}

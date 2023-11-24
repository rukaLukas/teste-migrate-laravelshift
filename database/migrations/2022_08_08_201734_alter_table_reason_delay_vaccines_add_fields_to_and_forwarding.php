<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableReasonDelayVaccinesAddFieldsToAndForwarding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reason_delay_vaccines', function (Blueprint $table) {
            $table->string('to')->nullable();
            $table->string('forwarding')->nullable()->comment('encaminhamento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reason_delay_vaccines', function (Blueprint $table) {
            $table->dropColumn('to');
            $table->dropColumn('forwarding');
        });
    }
}

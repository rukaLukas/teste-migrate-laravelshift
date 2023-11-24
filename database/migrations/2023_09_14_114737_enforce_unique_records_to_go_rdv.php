<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnforceUniqueRecordsToGoRdv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('go_rdv', function (Blueprint $table) {                       
            $table->unique(['reason_delay_vaccine_id', 'government_office_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('go_rdv', function (Blueprint $table) {
            // remove unique constraints
            $table->dropUnique(['reason_delay_vaccine_id', 'government_office_id']);
        });
    }
}

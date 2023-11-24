<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernmentOfficeReasonDelayVaccineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('go_rdv', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reason_delay_vaccine_id');
            $table->unsignedBigInteger('government_office_id'); 
            
            $table->foreign('reason_delay_vaccine_id')->references('id')->on('reason_delay_vaccines');
            $table->foreign('government_office_id')->references('id')->on('government_offices');            
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
            $table->dropForeign(['reason_delay_vaccine_id']);
            $table->dropForeign(['government_office_id']);            
        });
                
        Schema::dropIfExists('go_rdv');
    }
}

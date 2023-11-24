<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetPublicReasonDelayVaccineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_reason_delay_vaccine', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reason_delay_vaccine_id');
            $table->unsignedBigInteger('target_public_id');  
            
            $table->foreign('reason_delay_vaccine_id')->references('id')->on('reason_delay_vaccines');
            $table->foreign('target_public_id')->references('id')->on('target_publics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_reason_delay_vaccine');
    }
}

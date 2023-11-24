<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineScheduledAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_scheduled_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vaccine_id')->nullable(false);
            $table->unsignedBigInteger('alert_id')->nullable(false);
            $table->string('vaccination_step')->nullable();
            $table->date('previous_application')->nullalble(false);
            $table->date('next_application')->nullable();
            
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('alert_id')->references('id')->on('alerts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccine_scheduled_alerts');
    }
}

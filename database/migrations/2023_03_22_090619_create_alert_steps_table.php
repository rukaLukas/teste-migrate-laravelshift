<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_steps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('status_alert_id');
            $table->unsignedBigInteger('user_id');
            $table->string('comments')->nullable();
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('records');
            $table->foreign('status_alert_id')->references('id')->on('status_alerts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_steps');
    }
}

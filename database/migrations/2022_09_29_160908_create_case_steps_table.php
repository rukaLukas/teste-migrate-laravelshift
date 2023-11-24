<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_steps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->unsignedBigInteger('alert_id')->nullable(false);
            $table->tinyInteger('is_alert');
            $table->tinyInteger('is_analysis');
            $table->tinyInteger('is_forwarded');
            $table->tinyInteger('is_vaccineroom');
            $table->tinyInteger('is_done');
            $table->tinyInteger('is_closed');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('case_steps');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotAppliedVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_applied_vaccines', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('vaccine_id');
            $table->unsignedBigInteger('alert_step_id');
            $table->timestamps();

            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('alert_step_id')->references('id')->on('alert_steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('not_applied_vaccines', function (Blueprint $table) {
            $table->dropForeign(['vaccine_id']);
            $table->dropForeign(['alert_step_id']);
        });

        Schema::dropIfExists('not_applied_vaccines');
    }
}

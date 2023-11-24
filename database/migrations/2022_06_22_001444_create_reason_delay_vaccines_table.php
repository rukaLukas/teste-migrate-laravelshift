<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReasonDelayVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reason_delay_vaccines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('target_public_id');
            $table->string('description');
            $table->string('is_send_social_assistence');
            $table->timestamps();

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
        Schema::dropIfExists('reason_delay_vaccines');
    }
}

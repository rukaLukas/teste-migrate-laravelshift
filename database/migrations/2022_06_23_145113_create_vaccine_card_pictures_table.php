<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinecardPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_card_pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alert_id')->nullable(false);
            $table->string('image')->nullable(false);
            $table->timestamps();

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
        Schema::dropIfExists('vaccine_card_pictures');
    }
}

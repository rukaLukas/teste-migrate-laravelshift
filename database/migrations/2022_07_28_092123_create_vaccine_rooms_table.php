<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccineRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('postalcode', 10)->nullable(false);
            $table->string('street', 50)->nullable(false);
            $table->string('state', 2)->nullable(false);
            $table->string('city', 30)->nullable(false);
            $table->string('district', 30)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccine_rooms');
    }
}

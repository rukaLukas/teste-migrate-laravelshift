<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('target_public_id')->nullable(false);
            $table->uuid('uuid');
            $table->string('name');
            $table->string('schema');
            $table->string('dose')->nullable();
            $table->integer('aplication_age_month');
            $table->integer('limit_age_year');
            $table->integer('days_interval')->nullable();
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
        // $table->dropForeign('vaccines_target_public_id_foreign');
        Schema::dropIfExists('vaccines');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('target_public_id')->nullable(false);
            $table->unsignedBigInteger('breed_id')->nullable(false);
            $table->unsignedBigInteger('genre_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('cpf', 11)->nullalble();
            $table->string('rg', 11)->nullable();
            $table->date('birthdate')->nullable(false);
            $table->string('suscard', 15)->nullable();
            $table->string('mother_name')->nullable(false);
            $table->string('mother_rg', 11)->nullable();
            $table->string('mother_phone', 11)->nullable();
            $table->string('mother_mobilephone', 11)->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_rg', 11)->nullable();
            $table->string('father_phone', 11)->nullable();
            $table->string('father_mobilephone', 11)->nullable();
            $table->string('postalcode', 10)->nullable(false);
            $table->string('street', 50)->nullable(false);
            $table->string('state', 2)->nullable(false);
            $table->string('city', 30)->nullable(false);
            $table->string('district', 30)->nullable(false);
            $table->timestamps();

            $table->foreign('target_public_id')->references('id')->on('target_publics');
            $table->foreign('breed_id')->references('id')->on('breeds');
            $table->foreign('genre_id')->references('id')->on('genres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('alerts');
        Schema::enableForeignKeyConstraints();
    }
}

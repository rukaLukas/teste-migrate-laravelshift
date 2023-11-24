<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->unsignedBigInteger('county_id');
            $table->foreign('county_id')->references('id')->on('counties');

            $table->unsignedBigInteger('prefeito_id');
            $table->foreign('prefeito_id')->references('id')->on('users');

            $table->unsignedBigInteger('gestor_politico_id');
            $table->foreign('gestor_politico_id')->references('id')->on('users');

            $table->enum('status', ['aprovado', 'reprovado', 'pendente', 'ativo', 'aprovado_automaticamente'])
                ->default('pendente');

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
        Schema::dropIfExists('accessions');
    }
}

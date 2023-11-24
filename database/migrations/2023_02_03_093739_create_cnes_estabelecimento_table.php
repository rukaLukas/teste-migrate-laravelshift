<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnesEstabelecimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnes_estabelecimento', function (Blueprint $table) {
            $table->id();
            $table->string('CO_UNIDADE', 80)->nullable();
            $table->string('CO_CNES', 80)->nullable();
            $table->string('NO_FANTASIA', 80)->nullable();
            $table->string('NO_LOGRADOURO', 80)->nullable();
            $table->string('NU_ENDERECO', 80)->nullable();
            $table->string('NO_COMPLEMENTO', 80)->nullable();
            $table->string('NO_BAIRRO', 80)->nullable();
            $table->string('NU_TELEFONE', 80)->nullable();
            $table->string('NU_LATITUDE', 80)->nullable();
            $table->string('NU_LONGITUDE', 80)->nullable();
            $table->string('CO_MUNICIPIO_GESTOR', 80)->nullable();
            $table->string('CO_ESTADO_GESTOR', 80)->nullable();
            $table->string('CO_TIPO_UNIDADE', 80)->nullable();
            $table->string('CO_TIPO_ESTABELECIMENTO', 80)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cnes_estabelecimento');
    }
}

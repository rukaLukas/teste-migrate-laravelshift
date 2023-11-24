<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        // DB::unprepared( file_get_contents(resource_path('sql/create_candidatos.sql')) );  
        Schema::create('candidatos', function (Blueprint $table) {            
            $table->string('SG_UF')->nullable();
            $table->string('SG_UE')->nullable();
            $table->string('NM_UE')->nullable();
            $table->string('CD_CARGO')->nullable();
            $table->string('DS_CARGO')->nullable();
            $table->string('SQ_CANDIDATO')->nullable();
            $table->string('NR_CPF_CANDIDATO')->nullable();
            $table->string('CD_SIT_TOT_TURNO')->nullable();            
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidatos');
    }
}

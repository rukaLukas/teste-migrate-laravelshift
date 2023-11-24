<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsStatusConfirmedInAccessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accessions', function (Blueprint $table) {
            $table->enum('status_prefeito', ['pendente', 'confirmado'])
                ->default('pendente');
            $table->enum('status_gestor_politico', ['pendente', 'confirmado'])
                ->default('pendente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accessions', function (Blueprint $table) {
            $table->dropColumn('status_prefeito');
            $table->dropColumn('status_gestor_politico');
        });
    }
}

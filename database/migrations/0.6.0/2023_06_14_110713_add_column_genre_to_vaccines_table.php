<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnGenreToVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccines', function (Blueprint $table) {
            $table->string('genre')->after('target_public_id')->nullable()->comment('Gênero da vacina (M, F, M/F)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccines', function (Blueprint $table) {
            // drop column
            $table->dropColumn('genre');
        });
    }
}

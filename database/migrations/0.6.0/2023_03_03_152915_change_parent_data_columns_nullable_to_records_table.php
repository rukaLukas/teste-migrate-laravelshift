<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeParentDataColumnsNullableToRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->string('mother_name')->nullable()->change();
            $table->string('mother_cpf')->nullable()->change();
            $table->string('mother_email')->nullable()->change();
            $table->string('father_cpf')->nullable()->change();
            $table->string('father_email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->string('mother_name')->nullable(false)->change();
            $table->string('mother_cpf')->nullable(false)->change();
            $table->string('mother_email')->nullable(false)->change();
            $table->string('father_cpf')->nullable(false)->change();
            $table->string('father_email')->nullable(false)->change();
        });
    }
}

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsCountyidTypeToGovernmentOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('government_offices', function (Blueprint $table) {
            $table->unsignedBigInteger('county_id')->nullable(); 
            $table->char('type', 1)->nullable();

            $table->foreign('county_id')->references('id')->on('counties');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('government_offices', function (Blueprint $table) {
            $table->dropForeign(['county_id']);
        });
    }
}

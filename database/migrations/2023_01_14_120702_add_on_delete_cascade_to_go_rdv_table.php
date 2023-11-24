<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteCascadeToGoRdvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('go_rdv', function (Blueprint $table) {
            $table->dropForeign('go_rdv_government_office_id_foreign');
            $table->foreign('government_office_id')->references('id')->on('government_offices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('go_rdv', function (Blueprint $table) {            
            $table->foreign('government_office_id')->references('id')->on('government_offices'); 
        });
    }
}

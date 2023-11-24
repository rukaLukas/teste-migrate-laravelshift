<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAddressAndLatitudeLongitudeToUnderSubGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('under_sub_groups', function (Blueprint $table) {
            $table->string('logradouro')->nullable()->after('name');
            $table->string('endereco')->nullable()->after('logradouro');
            $table->string('bairro')->nullable()->after('endereco');
            $table->string('latitude')->nullable()->after('bairro');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('under_sub_groups', function (Blueprint $table) {
            $table->dropColumn('logradouro');
            $table->dropColumn('endereco');
            $table->dropColumn('bairro');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}

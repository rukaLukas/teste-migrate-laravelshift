<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCpfBirthdateCellphonePositionOfficephoneColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthdate')->after('remember_token')->nullable();
            $table->string('cpf', 14)->after('birthdate')->nullable();
            $table->string('cell_phone', 14)->after('cpf')->nullable();
            $table->string('office_phone', 14)->after('cell_phone')->nullable();
            $table->string('position', 30)->after('office_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cpf',
                'cell_phone',
                'office_phone',
                'position'
            ]);
        });
    }
}

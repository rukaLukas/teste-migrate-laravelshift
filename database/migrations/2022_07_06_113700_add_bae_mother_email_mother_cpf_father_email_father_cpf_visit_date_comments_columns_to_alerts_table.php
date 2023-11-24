<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBaeMotherEmailMotherCpfFatherEmailFatherCpfVisitDateCommentsColumnsToAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->string('mother_cpf', 11)->after('mother_mobilephone')->nullalble(true);
            $table->string('mother_email', 50)->after('mother_cpf')->nullalble(true); 
            $table->string('father_cpf', 11)->after('father_mobilephone')->nullalble(true);
            $table->string('father_email', 50)->after('father_cpf')->nullalble(true); 
            $table->string('bae', 1)->nullable(true);
            $table->date('visit_date')->nullable(false);
            $table->text('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn([
                'mother_cpf',
                'mother_email',
                'father_cpf',
                'father_email',
                'bae',
                'visit_date',
                'comments'
            ]);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnRecordIdNullableFalseToAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            // create a defaut record in records table
            $record = new \App\Models\Record();
            $record->cpf = '00000000000';
            $record->suscard = '00000000';
            $record->save();

            // update all records in alerts table to record_id = 1
            \App\Models\Alert::query()->update(['record_id' => 1]);

            // change column record_id to nullable(false)
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement('ALTER TABLE `alerts` MODIFY COLUMN `record_id` BIGINT UNSIGNED NOT NULL');                   
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
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
            $table->unsignedBigInteger('record_id')->nullable(true)->change();    
        });
    }
}

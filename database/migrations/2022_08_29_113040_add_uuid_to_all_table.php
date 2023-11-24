<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToAllTable extends Migration
{
    public $tables = [
        'alerts',
        'breeds',
        'genres',
        'government_agencies',
        'profiles',
        'users',
        'vaccine_rooms',
        'type_status_vaccinations',
        'reason_delay_vaccines',
        'target_publics',
        'vaccine_scheduled_alerts',
    ];

    public $column = 'uuid';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $column = $this->column;
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->uuid($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $column = $this->column;
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn('uuid');
            });
        }
    }
}

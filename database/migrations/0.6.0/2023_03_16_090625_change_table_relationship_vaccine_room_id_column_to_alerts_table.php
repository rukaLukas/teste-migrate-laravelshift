<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableRelationshipVaccineRoomIdColumnToAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            DB::statement('UPDATE alerts a
                            LEFT JOIN under_sub_groups usg ON a.vaccine_room_id = usg.id
                            SET a.vaccine_room_id = NULL
                            WHERE usg.id IS NULL');

            // if(env('DB_DATABASE') == 'test_bav') {
            //     $table->dropForeign('alerts_vaccine_room_id_foreign');
            // }
            $table->dropForeign('alerts_vaccine_room_id_foreign');
            $table->foreign('vaccine_room_id')->references('id')->on('under_sub_groups');
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
            DB::statement('UPDATE alerts a
                            LEFT JOIN vaccine_rooms vr ON a.vaccine_room_id = vr.id
                            SET a.vaccine_room_id = NULL
                            WHERE vr.id IS NULL');

            // $table->dropIndex('alerts_vaccine_room_id_foreign');
            $table->dropForeign('vaccine_room_id');

            // $table->dropForeign('under_sub_groups_id');
            // $table->foreign('vaccine_room_id')->references('id')->on('vaccine_rooms');
        });
    }
}

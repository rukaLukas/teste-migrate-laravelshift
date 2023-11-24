<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReasonDelayVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reason_delay_vaccines', function (Blueprint $table) {            
            $table->tinyInteger('is_forwarding')->default(0);
            $table->unsignedBigInteger('type_reason_delay_vaccine_id')->nullable();
            $table->foreign('type_reason_delay_vaccine_id')->references('id')->on('type_reason_delay_vaccines');


            $table->dropForeign(['target_public_id']);

            $table->dropColumn([
                'is_send_social_assistence',
                'to',
                'forwarding',
                'target_public_id'
            ]);         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reason_delay_vaccines', function (Blueprint $table) { 

            $table->unsignedBigInteger('target_public_id')->nullable();            
            $table->string('is_send_social_assistence');
            $table->string('forwarding');
            $table->string('to');            
            $table->foreign('target_public_id')->references('id')->on('target_publics');
            
            $table->dropForeign(['type_reason_delay_vaccine_id']);
            $table->dropColumn([
                'is_forwarding',
                'type_reason_delay_vaccine_id',                
            ]);         
        });
    }
}

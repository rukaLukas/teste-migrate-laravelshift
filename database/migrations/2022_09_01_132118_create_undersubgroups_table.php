<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndersubgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('under_sub_groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');

            $table->unsignedBigInteger('sub_group_id');
            $table->foreign('sub_group_id')->references('id')->on('sub_groups');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('under_sub_groups');
    }
}

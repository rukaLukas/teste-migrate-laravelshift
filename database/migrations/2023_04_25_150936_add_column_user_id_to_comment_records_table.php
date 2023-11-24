<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserIdToCommentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_records', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_records', function (Blueprint $table) {
            $table->dropForeign(['comment_records_user_id']);
            $table->dropColumn('user_id');
        });
    }
}

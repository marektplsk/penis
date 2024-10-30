<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourSessionToWinHistoryTable extends Migration
{
    public function up()
    {
        Schema::table('win_history', function (Blueprint $table) {
            $table->string('hour_session')->nullable(); // Add the hour_session column
        });
    }

    public function down()
    {
        Schema::table('win_history', function (Blueprint $table) {
            $table->dropColumn('hour_session'); // Remove the hour_session column if rolled back
        });
    }
}

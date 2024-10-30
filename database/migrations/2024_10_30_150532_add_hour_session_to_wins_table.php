<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourSessionToWinsTable extends Migration
{
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->string('hour_session')->nullable(); // Use appropriate type based on your needs
        });
    }

    public function down()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->dropColumn('hour_session');
        });
    }
}


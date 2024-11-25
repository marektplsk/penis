<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPairToWinsTable extends Migration
{
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->string('pair')->after('description'); // Add the pair column after the description column
        });
    }

    public function down()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->dropColumn('pair'); // Drop the pair column if the migration is rolled back
        });
    }
}

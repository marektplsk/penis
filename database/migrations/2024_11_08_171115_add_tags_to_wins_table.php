<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTagsToWinsTable extends Migration
{
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            if (!Schema::hasColumn('wins', 'tags')) {
                $table->text('tags')->nullable(); // Add the tags column
            }
        });
    }

    public function down()
    {
        Schema::table('wins', function (Blueprint $table) {
            if (Schema::hasColumn('wins', 'tags')) {
                $table->dropColumn('tags'); // Drop the tags column if it exists
            }
        });
    }
}

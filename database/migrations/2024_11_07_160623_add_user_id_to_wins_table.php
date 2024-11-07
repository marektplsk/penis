<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            if (!Schema::hasColumn('wins', 'user_id')) {
                $table->unsignedBigInteger('user_id'); // Add user_id field
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Add foreign key constraint
            }
        });
    }


    public function down()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop foreign key constraint
            $table->dropColumn('user_id'); // Drop user_id field
        });
    }

};

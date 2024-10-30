<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('payload');
                $table->integer('last_activity');
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('user_id')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinsTable extends Migration
{
    public function up()
    {
        Schema::create('wins', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('description'); // Column for the win/loss description
            $table->boolean('is_win'); // Boolean column for win/loss status
            $table->decimal('risk', 8, 2); // Column for risk value
            $table->decimal('risk_reward_ratio', 8, 2); // Column for risk/reward ratio
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('wins');
    }
}

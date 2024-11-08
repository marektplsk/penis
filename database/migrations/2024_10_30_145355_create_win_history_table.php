<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('win_history', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('description'); // Field for the win description
            $table->boolean('is_win'); // Field for win/loss status
            $table->decimal('risk', 10, 2); // Field for risk
            $table->decimal('risk_reward_ratio', 10, 2); // Field for risk/reward ratio
            $table->timestamp('created_at')->nullable(); // Original creation timestamp
            $table->timestamp('deleted_at')->nullable(); // Timestamp for when the record was deleted
            $table->string('data'); // Field for the data
        });
    }

    public function down()
    {
        Schema::dropIfExists('win_history'); // Drop table on rollback
    }
}

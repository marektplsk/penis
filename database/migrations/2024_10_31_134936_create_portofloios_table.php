<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortofloiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('portfolios', function (Blueprint $table) {
        $table->id();
        $table->decimal('amount', 15, 2); // Assuming amount is a decimal value
        $table->string('type', 255); // Assuming type is a string with a max length of 255
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
    Schema::dropIfExists('portfolios');
}
}

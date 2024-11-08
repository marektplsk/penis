<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTradeTypeToWinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->string('trade_type')->after('data'); // Add the trade_type column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->dropColumn('trade_type'); // Drop the trade_type column if the migration is rolled back
        });
    }
}

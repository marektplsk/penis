
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToWinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wins', function (Blueprint $table) {
            $table->string('data')->after('user_id'); // Add the data column
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
            $table->dropColumn('data'); // Drop the data column if the migration is rolled back
        });
    }
}

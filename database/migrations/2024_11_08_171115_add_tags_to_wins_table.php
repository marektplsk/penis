<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
public function up()
{
Schema::create('tags', function (Blueprint $table) {
$table->id();                        // Creates an auto-incrementing 'id' column as the primary key
$table->string('name')->unique();     // Creates a 'name' column that must be unique
$table->timestamps();                 // Creates 'created_at' and 'updated_at' timestamp columns
});
}

public function down()
{
Schema::dropIfExists('tags');             // Drops the 'tags' table if it exists, rolling back the migration
}
}

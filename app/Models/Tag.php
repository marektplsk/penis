<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Define the table associated with the model (if different from pluralized class name)
    protected $table = 'tags';

    // Define fillable properties
    protected $fillable = [
        'name',
    ];

    // Optionally, you can define timestamps if you're using custom columns
    public $timestamps = true;
}

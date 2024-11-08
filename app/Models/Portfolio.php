<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolios';

    protected $fillable = ['amount', 'type', 'user_id', 'created_at', 'updated_at'];

    public $timestamps = true;

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

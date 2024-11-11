<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinModel extends Model
{
    use HasFactory;

    // Define the table associated with the model (if different from pluralized class name)
    protected $table = 'wins'; // Adjust if your table name is different

    // Define fillable properties
    protected $fillable = [
        'description',
        'is_win',
        'risk',
        'risk_reward_ratio',
        'created_at',
        'updated_at',
        'hour_session',
        'portfolio_id',
        'user_id',
        'data',
        'trade_type',
        'tags',

    ];

    // Optionally, you can define timestamps if you're using custom columns


    public $timestamps = true;


}

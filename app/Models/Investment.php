<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'debtor',
        'interests',
        'frequency',
        'final_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'goal_id',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}

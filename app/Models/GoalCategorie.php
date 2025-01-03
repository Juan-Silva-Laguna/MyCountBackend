<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalCategorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}

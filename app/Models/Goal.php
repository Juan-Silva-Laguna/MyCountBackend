<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'name',
        'description',
        'frequency',
        'final_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savings()
    {
        return $this->hasMany(Goal::class);
    }

    
    public function goalCategories()
    {
        return $this->hasMany(GoalCategorie::class);
    }
}

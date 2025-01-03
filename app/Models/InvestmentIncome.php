<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentIncome extends Model
{
    use HasFactory;

    protected $table = 'investments_income';
    
    protected $fillable = [
        'investment_id',
        'income_id'
    ];

    public $timestamps = false;

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }
}

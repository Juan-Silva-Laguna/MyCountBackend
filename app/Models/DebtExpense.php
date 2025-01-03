<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtExpense extends Model
{
    use HasFactory;

    protected $table = 'debts_expenses';
    
    protected $fillable = [
        'debt_id',
        'expense_id'
    ];

    public $timestamps = false;

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Debt;
use App\Models\DebtExpense;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\GoalCategorie;
use App\Models\Investment;
use App\Models\Saving;
use Illuminate\Support\Facades\DB;

class GoalsController extends Controller
{
    public function index()
    {
        $goal = Goal::join('goal_categories', 'goals.goal_categories_id','goal_categories.id')->where('goals.user_id', Auth::user()->id)->get();
        return response()->json(['data' => $goal]);
    }

    public function store(Request $request)
    {
        if ($request->goal_categories_id ==null) {
            $goalCategorie = new GoalCategorie();
            $goalCategorie->name = $request->nameGoalCategorie;
            $goalCategorie->icon = $request->icon;
            $goalCategorie->color = $request->color;
            $goalCategorie->save();
            $goal_categories_id = $goalCategorie->id;
        }else{
            $goal_categories_id = $request->goal_categories_id;
        }
        
        $goal = new Goal();
        $goal->amount = $request->amount;
        $goal->name = $request->name;
        $goal->description = $request->description;
        $goal->frequency = $request->frequency;
        $goal->final_date = $request->final_date;
        $goal->goal_categories_id = $goal_categories_id;
        $goal->user_id = Auth::user()->id;
        $goal->save();
        
        return response()->json(['message' => "Meta Agregada"]);
    }
}

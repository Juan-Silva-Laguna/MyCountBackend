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

class SavingsController extends Controller
{
    public function index()
    {
        $saving = Saving::join('goals', 'savings.goal_id','goals.id')->select('savings.*')->where('goals.user_id', Auth::user()->id)->get();
        return response()->json(['data' => $saving]);
    }


    public function store(Request $request)
    {
        $savingTot = Saving::join('goals', 'savings.goal_id', 'goals.id')->select(DB::raw('SUM(savings.amount) as totSaving'))->where('goals.user_id', Auth::user()->id)->first();
        $expenseTot = Expense::select(DB::raw('SUM(amount) as totExpense'))->where('user_id', Auth::user()->id)->first();  
        $investmentTot = Investment::select(DB::raw('SUM(amount) as totInvestment'))->where('user_id', Auth::user()->id)->first();        
        $incomeTot = Income::select(DB::raw('SUM(amount) as totIncome'))->where('user_id', Auth::user()->id)->first(); 
        $debtTot = Debt::select(DB::raw('SUM(amount) as totDebt'))->where('user_id', Auth::user()->id)->first();
        
        if ($incomeTot->totIncome == null || $debtTot->totDebt == null) {
            return response()->json(['message' => "Inicialmente debes tener un ingreso"]);
        } elseif (($incomeTot->totIncome+$debtTot->totDebt) < (($expenseTot->totExpense+$investmentTot->totInvestment+$savingTot->totSaving)+$request->amount)) {
            return response()->json(['message' => "No puedes agragar el gasto, por favor revisa tus cuentas y vuelve a intentarlo"]);
        }
        
        $saving = new Saving();
        $saving->amount = $request->amount;
        $saving->description = $request->description;
        $saving->goal_id = $request->goal_id;
        $saving->save();

        return response()->json(['message' => "Ahorro Agregado"]);
    }

    public function show($id)
    {
        $saving = Saving::where('saving.goal_id', $id)->get();
        return response()->json(['data' => $saving]);
    }
    
}

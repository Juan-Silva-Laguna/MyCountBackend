<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Debt;
use App\Models\Investment;
use App\Models\Saving;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    public function index()
    {
        $expense = Expense::where('user_id', Auth::user()->id)->get();
        $investment = Investment::where('user_id', Auth::user()->id)->get();
        return response()->json(['dataExpense' => $expense, 'dataInvestment' => $investment]);
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

        $expense = new Expense();
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->user_id = Auth::user()->id;
        $expense->save();

        return response()->json(['message' => "Gasto Agregado"]);
    }

    public function show($id)
    {
        $expense = Expense::where('id', $id)->first();
        return response()->json(['data' => $expense]);
    }

    public function update(Request $request)
    {
        $savingTot = Saving::join('goals', 'savings.goal_id', 'goals.id')->select(DB::raw('SUM(savings.amount) as totSaving'))->where('goals.user_id', Auth::user()->id)->first();
        $expenseTot = Expense::select(DB::raw('SUM(amount) as totExpense'))->where('user_id', Auth::user()->id)->first();  
        $investmentTot = Investment::select(DB::raw('SUM(amount) as totInvestment'))->where('user_id', Auth::user()->id)->first();        
        $incomeTot = Income::select(DB::raw('SUM(amount) as totIncome'))->where('user_id', Auth::user()->id)->first(); 
        $debtTot = Debt::select(DB::raw('SUM(amount) as totDebt'))->where('user_id', Auth::user()->id)->first();
        if (($incomeTot->totIncome+$debtTot->totDebt) < (($expenseTot->totExpense+$investmentTot->totInvestment+$savingTot->totSaving)+$request->amount)) {
            return response()->json(['message' => "No puedes editar el gasto, por favor revisa tus cuentas y vuelve a intentarlo"]);
        }
        
        $expense = Expense::findOrFail($request->id);
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->save();
        return response()->json(['message' => "Gasto Actualizado"]);
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Debt;
use App\Models\DebtExpense;
use App\Models\Expense;
use App\Models\Investment;
use App\Models\Saving;
use Illuminate\Support\Facades\DB;

class DebtsController extends Controller
{
    public function index()
    {
        $debt = Debt::where('user_id', Auth::user()->id)->get();
        return response()->json(['data' => $debt]);
    }

    public function store(Request $request)
    {
        $debt = new Debt();
        $debt->amount = $request->amount;
        $debt->description = $request->description;
        $debt->lender = $request->lender;
        $debt->interests = $request->interests;
        $debt->frequency = $request->frequency;
        $debt->final_date = $request->final_date;
        $debt->user_id = Auth::user()->id;
        $debt->save();

        return response()->json(['message' => "Deuda Agregada"]);
    }

    public function show($id)
    {
        $debt = Debt::where('id', $id)->first();
        return response()->json(['data' => $debt]);
    }

    public function validationDebt(Request $request)
    {
        $savingTot = Saving::join('goals', 'savings.goal_id', 'goals.id')->select(DB::raw('SUM(savings.amount) as totSaving'))->where('goals.user_id', Auth::user()->id)->first();
        $debtTot = Debt::select(DB::raw('SUM(amount) as totDebt'))->where('user_id', Auth::user()->id)->first();
        $expensiveTot = Expense::select(DB::raw('SUM(amount) as totExpensive'))->where('user_id', Auth::user()->id)->first(); 
        $investmentTot = Investment::select(DB::raw('SUM(amount) as totInvestment'))->where('user_id', Auth::user()->id)->first();         
        $incomeTot = Income::select(DB::raw('SUM(amount) as totIncome'))->where('user_id', Auth::user()->id)->first(); 
        $result = ($incomeTot->totIncome+$debtTot->totDebt) - ($expensiveTot->totExpensive+$investmentTot->totInvestment+$savingTot->totSaving);
        if ($request->amount<=$result) {
            return response()->json(['validation' => 0, 'result' => $result]);
        }

        return response()->json(['validation' => 1, 'result' => $result]);
    }

    public function addExpense(Request $request)
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

        $debtExpense = new DebtExpense();
        $debtExpense->debt_id = $request->debt_id;
        $debtExpense->expense_id = $expense->id;
        $debtExpense->save();
    }

    public function update(Request $request)
    {
        $debt = Debt::findOrFail($request->id);
        $debt->amount = $request->amount;
        $debt->description = $request->description;
        $debt->lender = $request->lender;
        $debt->interests = $request->interests;
        $debt->frequency = $request->frequency;
        $debt->final_date = $request->final_date;
        $debt->save();
        return response()->json(['message' => "Deuda Actualizada"]);
    }
    
}

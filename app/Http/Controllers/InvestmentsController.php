<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Investment;
use App\Models\InvestmentIncome;
use App\Models\Saving;
use Illuminate\Support\Facades\DB;

class InvestmentsController extends Controller
{
    public function index()
    {
        $investment = Investment::where('user_id', Auth::user()->id)->get();
        return response()->json(['data' => $investment]);
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

        $investment = new Investment();
        $investment->amount = $request->amount;
        $investment->description = $request->description;
        $investment->debtor = $request->debtor;
        $investment->interests = $request->interests;
        $investment->frequency = $request->frequency;
        $investment->final_date = $request->final_date;
        $investment->user_id = Auth::user()->id;
        $investment->save();

        return response()->json(['message' => "Inversion Agregada"]);
    }

    public function show($id)
    {
        $investment = Investment::where('id', $id)->first();
        return response()->json(['data' => $investment]);
    }

    public function addIncome(Request $request)
    {
        $income = new Income();
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->user_id = Auth::user()->id;
        $income->save();

        $investmentIncome = new InvestmentIncome();
        $investmentIncome->investment_id = $request->investment_id;
        $investmentIncome->income_id = $income->id;
        $investmentIncome->save();
    }

    public function update(Request $request)
    {
        $savingTot = Saving::join('goals', 'savings.goal_id', 'goals.id')->select(DB::raw('SUM(savings.amount) as totSaving'))->where('goals.user_id', Auth::user()->id)->first();
        $expenseTot = Expense::select(DB::raw('SUM(amount) as totExpense'))->where('user_id', Auth::user()->id)->first();  
        $investmentTot = Investment::select(DB::raw('SUM(amount) as totInvestment'))->where('user_id', Auth::user()->id)->first();        
        $incomeTot = Income::select(DB::raw('SUM(amount) as totIncome'))->where('user_id', Auth::user()->id)->first(); 
        $debtTot = Debt::select(DB::raw('SUM(amount) as totDebt'))->where('user_id', Auth::user()->id)->first();
        
        if (($incomeTot->totIncome+$debtTot->totDebt) < ($expenseTot->totExpense+$investmentTot->totInvestment+$savingTot->totSaving)) {
            return response()->json(['message' => "No puedes editar la inversion, por favor revisa tus cuentas y vuelve a intentarlo"]);
        }

        $investment = Investment::findOrFail($request->id);
        $investment->amount = $request->amount;
        $investment->description = $request->description;
        $investment->debtor = $request->debtor;
        $investment->interests = $request->interests;
        $investment->frequency = $request->frequency;
        $investment->final_date = $request->final_date;
        $investment->save();
        return response()->json(['message' => "Inversion Actualizada"]);
    }
    
}

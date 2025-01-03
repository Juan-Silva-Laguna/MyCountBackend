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

class IncomeController extends Controller
{
    public function index()
    {
        $income = Income::where('user_id', Auth::user()->id)->get();
        $debts = Debt::where('user_id', Auth::user()->id)->get();
        return response()->json(['dataIncome' => $income, 'dataDebts' => $debts]);
    }

    public function store(Request $request)
    {
        $income = new Income();
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->user_id = Auth::user()->id;
        $income->save();

        return response()->json(['message' => "Ingreso Agregado"]);
    }

    public function show($id)
    {
        $income = Income::where('id', $id)->first();
        return response()->json(['data' => $income]);
    }

    public function update(Request $request)
    {
        $savingTot = Saving::join('goals', 'savings.goal_id', 'goals.id')->select(DB::raw('SUM(savings.amount) as totSaving'))->where('goals.user_id', Auth::user()->id)->first();
        $expensiveTot = Expense::select(DB::raw('SUM(amount) as totExpensive'))->where('user_id', Auth::user()->id)->first();
        $investmentTot = Investment::select(DB::raw('SUM(amount) as totInvestment'))->where('user_id', Auth::user()->id)->first();        
        $incomeTot = Income::select(DB::raw('SUM(amount) as totIncome'))->where('user_id', Auth::user()->id)->first();
        $debtTot = Debt::select(DB::raw('SUM(amount) as totDebt'))->where('user_id', Auth::user()->id)->first();
        $IncomeId = Income::select('amount as income')->where('id', $request->id)->first();
        $resultIncome = (($incomeTot->totIncome+$debtTot->totDebt) - $IncomeId->income)+$request->amount;
        if ($resultIncome < ($expensiveTot->totExpensive+$investmentTot->totInvestment+$savingTot->totSaving)) {
            return response()->json(['message' => "No puedes editar el ingreso, por favor revisa tus cuentas y vuelve a intentarlo"]);
        }

        $income = Income::findOrFail($request->id);
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->save();
        return response()->json(['message' => "Ingreso Actualizado"]);
    }
    
}

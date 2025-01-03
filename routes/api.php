<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;    
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\DebtsController;
use App\Http\Controllers\InvestmentsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.verify']], function() {
    //-- Auth

    // Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    //             ->middleware('auth')
    //             ->name('verification.notice');

    // Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    //                 ->middleware(['auth', 'signed', 'throttle:6,1'])
    //                 ->name('verification.verify');

    // Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //                 ->middleware(['auth', 'throttle:6,1'])
    //                 ->name('verification.send');

    // Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    //                 ->middleware('auth')
    //                 ->name('password.confirm');

    // Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    //                 ->middleware('auth');

    Route::get('/validation', [HomeController::class, 'validation'])->name('validation');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


    //--Income
    Route::get('/income/index', [IncomeController::class, 'index'])->name('incomeIndex');
    Route::post('/income/store', [IncomeController::class, 'store'])->name('incomeStore');
    Route::put('/income/update', [IncomeController::class, 'update'])->name('incomeUpdate');

    //--Expenses
    Route::get('/expense/index', [ExpensesController::class, 'index'])->name('expenseIndex');
    Route::post('/expense/store', [ExpensesController::class, 'store'])->name('expenseStore');
    Route::put('/expense/update', [ExpensesController::class, 'update'])->name('expenseUpdate');

    //--Debts
    Route::get('/debt/index', [DebtsController::class, 'index'])->name('debtIndex');
    Route::post('/debt/store', [DebtsController::class, 'store'])->name('debtStore');
    Route::put('/debt/update', [DebtsController::class, 'update'])->name('debtUpdate');
    Route::post('/debt/validation', [DebtsController::class, 'validationDebt'])->name('debtValidation');

    //--Investments
    Route::get('/investment/index', [InvestmentsController::class, 'index'])->name('investmentIndex');
    Route::post('/investment/store', [InvestmentsController::class, 'store'])->name('investmentStore');
    Route::put('/investment/update', [InvestmentsController::class, 'update'])->name('investmentUpdate');

    //--Goals
    Route::get('/goal/index', [GoalsController::class, 'index'])->name('goalIndex');
    Route::post('/goal/store', [GoalsController::class, 'store'])->name('goalStore');

    //--Savings
    Route::get('/saving/index', [SavingsController::class, 'index'])->name('savingIndex');
    Route::get('/saving/show', [SavingsController::class, 'show'])->name('savingShow');
    Route::post('/saving/store', [SavingsController::class, 'store'])->name('savingStore');
    
});

require __DIR__.'/auth.php';
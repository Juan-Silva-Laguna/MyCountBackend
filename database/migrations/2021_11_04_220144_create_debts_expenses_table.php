<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts_expenses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('debt_id')->unsigned();
            $table->foreign('debt_id')->references('id')->on('debts');
            $table->bigInteger('expense_id')->unsigned();
            $table->foreign('expense_id')->references('id')->on('expenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts_expenses');
    }
}

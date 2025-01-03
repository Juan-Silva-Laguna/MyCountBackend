<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments_income', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('investment_id')->unsigned();
            $table->foreign('investment_id')->references('id')->on('investments');
            $table->bigInteger('income_id')->unsigned();
            $table->foreign('income_id')->references('id')->on('income');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments_income');
    }
}

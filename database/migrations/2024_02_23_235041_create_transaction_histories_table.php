<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_account_id')->unsigned()->index()->nullable(false);
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('transaction_type', ['DEPOSIT', 'WITHDRAWAL', 'TRANSFER'])->nullable(false);
            $table->enum('transaction_action', ['CREDIT', 'DEBIT'])->nullable(false);
            $table->string('reference', 128)->nullable(false);
            $table->decimal('amount', 13, 2, true)->nullable(false)->default(0.00);
            $table->decimal('charges', 13, 2,)->nullable(false)->default(0.00);
            $table->decimal('total_transaction_amount', 13, 2)->nullable(false)->default(0.00);
            $table->decimal('account_balance_after_transaction', 13, 2)->nullable(false)->default(0.00);
            $table->bigInteger('targeted_external_bank_account_id')->unsigned()->index()->nullable();
            $table->foreign('targeted_external_bank_account_id')->references('id')->on('bank_accounts')->onDelete('restrict')->onUpdate('cascade');
            $table->string('comments', 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_histories');
    }
}

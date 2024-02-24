<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 64)->nullable(false);
            $table->enum('account_type', ['CURRENT', 'SAVINGS'])->nullable(false);
            $table->decimal('account_balance', 13, 2)->nullable(false)->default(0.00);
            $table->bigInteger('customer_id')->unsigned()->index()->nullable(false);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('created_at_branch')->unsigned()->index()->nullable(false);
            $table->foreign('created_at_branch')->references('id')->on('bank_branches')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable(false);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->boolean('is_active')->nullable(false)->default(true);
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
        Schema::dropIfExists('bank_accounts');
    }
}

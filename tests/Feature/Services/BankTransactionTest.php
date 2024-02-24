<?php

namespace Tests\Feature\Services;

use App\Models\BankAccount;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BankTransactionTest extends TestCase
{
    public function test_should_successfully_transfer()
    {
        $fromAccount = null;
        if (BankAccount::count() == 0) {
            $fromAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $fromAccount = BankAccount::all()->first();
        }
        $fromAccount->account_balance = 5000000.0;
        $fromAccount->save();

        $toAccount = null;
        if (BankAccount::count() < 2) {
            $toAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $toAccount = BankAccount::all()->last();
        }

        $requestBody = [
            'from' => $fromAccount->account_number,
            'to' => $toAccount->account_number,
            'amount' => 15000.0,
            'charges' => 0.0,
            'comments' => 'Transfer from ' . Str::uuid()->toString()
        ];


        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->withMiddleware(['sanctum'])
            ->withHeaders($this->defaultJsonHeaders())
            ->postJson(route('transactions.transfer'), $requestBody);

        $response
            ->assertStatus(200)
        ->assertJson([
            'bank_account_id' => $fromAccount->id,
            'transaction_type' => 'TRANSFER',
            "transaction_action" => "DEBIT",
            'amount' => 15000.0,
            'charges' => 0.0,
            'total_transaction_amount' => -15000.0 + 0.0,
            'account_balance_after_transaction' => $fromAccount->account_balance - (15000 + 0), // account - (amountSent + charges)
            'targeted_external_bank_account_id' => $toAccount->id
        ]);
    }

    public function test_should_successfully_deposit()
    {
        $bankAccount = null;
        if (BankAccount::count() == 0) {
            $bankAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $bankAccount = BankAccount::all()->first();
        }

        $requestBody = [
            'account_number' => $bankAccount->account_number,
            'amount' => 15000.0,
            'charges' => 0.0,
            'comments' => 'Deposit ' . Str::uuid()->toString()
        ];

        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->withMiddleware(['sanctum'])
            ->withHeaders($this->defaultJsonHeaders())
            ->postJson(route('transactions.deposit'), $requestBody);

        $response
            ->assertStatus(200)
            ->assertJson([
                'bank_account_id' => $bankAccount->id,
                'transaction_type' => 'DEPOSIT',
                "transaction_action" => "CREDIT",
                'amount' => 15000.0,
                'charges' => 0.0,
                'total_transaction_amount' => 15000.0,
                'account_balance_after_transaction' => $bankAccount->account_balance + 15000
            ]);
    }

    public function test_should_successfully_withdraw()
    {
        $bankAccount = null;
        if (BankAccount::count() == 0) {
            $bankAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $bankAccount = BankAccount::all()->first();
        }

        $requestBody = [
            'account_number' => $bankAccount->account_number,
            'amount' => 15000.0,
            'charges' => 0.0,
            'comments' => 'Withdraw ' . Str::uuid()->toString()
        ];


        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->withMiddleware(['sanctum'])
            ->withHeaders($this->defaultJsonHeaders())
            ->postJson(route('transactions.withdraw'), $requestBody);

        $response
            ->assertStatus(200)
            ->assertJson([
                'bank_account_id' => $bankAccount->id,
                'transaction_type' => 'WITHDRAWAL',
                'amount' => 15000.0,
                'charges' => 0.0,
                'total_transaction_amount' => -15000.0 + 0.0,
                'account_balance_after_transaction' => $bankAccount->account_balance - (15000.0 + 0.0)
            ]);
    }
}

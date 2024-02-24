<?php

namespace Tests\Feature\Services;

use App\Models\BankAccount;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BankAccountTest extends TestCase
{
    public function test_should_create_a_bank_account_successfully()
    {
        $amount = 3000000.0;
        $type = 'SAVINGS';

        $customer = null;
        if (Customer::count() == 0) {
            $customer = Customer::factory()->count(1)->create()->first();
        } else {
            $customer = Customer::all()->first();
        }

        $requestBody = [
            'account_type' => $type,
            'initial_deposit' => $amount,
            'customer_id' => $customer->id,
            'created_at_branch' => $customer->created_at_branch,
        ];

        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->withMiddleware(['sanctum'])
            ->withHeaders($this->defaultJsonHeaders())
            ->postJson(route('bank-accounts.store'), $requestBody);

        $response
            ->assertStatus(201);
    }

    public function test_should_successfully_retrieve_account_balance()
    {
        $bankAccount = null;
        if (BankAccount::count() == 0) {
            $bankAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $bankAccount = BankAccount::all()->first();
        }

        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->getJson(route('bank-accounts.account-balance', ['bank_account' => $bankAccount->id]));

        $response
            ->assertOk()
            ->assertJson([
                'account_balance' => $bankAccount->account_balance
            ]);
    }

    public function test_should_fetch_one_transactions()
    {
        $bankAccount = null;
        if (BankAccount::count() == 0) {
            $bankAccount = BankAccount::factory()->count(1)->create()->first();
        } else {
            $bankAccount = BankAccount::all()->first();
        }

        Sanctum::actingAs($this->userForAuth(), ["*"]);

        $response = $this->getJson(route('bank-accounts.show', ['bank_account' => $bankAccount->id]));

        $response
            ->assertOk();
    }
}

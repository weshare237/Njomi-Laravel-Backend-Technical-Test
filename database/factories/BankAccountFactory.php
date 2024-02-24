<?php

namespace Database\Factories;

use App\Models\BankBranch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $branch = BankBranch::all()->first();
        $user = User::all()->first();
        $customer = Customer::all()->first();
        if ($branch == null) {
            $branch = BankBranch::factory()->count(1)->create()->first();
        }
        if ($user == null) {
            $user = User::factory()->count(1)->create()->first();
        }
        if ($customer == null) {
            $customer = Customer::factory()->count(1)->create()->first();
        }
        return [
            'account_number' => $this->faker->iban('CMR'),
            'account_type' => $this->faker->randomElements(['CURRENT', 'SAVINGS'])[0],
            'account_balance' => 300000.0,
            'customer_id' => $customer->id,
            'created_at_branch' => $branch->id,
            'created_by' => $user->id,
        ];
    }
}

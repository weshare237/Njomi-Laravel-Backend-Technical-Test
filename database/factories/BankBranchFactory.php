<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankBranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bank = Bank::all()->first();
        if ($bank == null) {
            $bank = Bank::factory()->count(1)->create()->first();
        }
        $arr = [];
        $arr['name'] = $bank->name . ' - Head Office';
        $arr['address_line_1'] = $this->faker->streetAddress();
        $arr['address_line_2'] = $this->faker->address();
        $arr['state'] = 'NW';
        $arr['country'] = 'CMR';
        $arr['bank_id'] = $bank->id;
        return $arr;
    }
}

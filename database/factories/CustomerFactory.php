<?php

namespace Database\Factories;

use App\Models\BankBranch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $branch = BankBranch::all()->first();
        if ($branch == null) {
            $branch = BankBranch::factory()->count(1)->create()->first();
        }
        $user = User::all()->first();
        if ($user == null) {
            $user = User::factory()->count(1)->create()->first();
        }
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'sex' => $this->faker->randomElements(['M', 'F'])[0],
            'date_of_birth' => $this->faker->date('Y-m-d', '2002-04-04'),
            'place_of_birth' => $this->faker->city,
            'nationality' => 'CMR',
            'country_of_origin' => 'CMR',
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'created_at_branch' => $branch->id,
            'created_by' => $user->id
        ];
    }
}

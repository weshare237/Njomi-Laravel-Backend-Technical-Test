<?php

namespace Tests;

use App\Models\User;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication, DatabaseMigrations;

    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        Artisan::call('migrate:refresh');
    }

    public function __get($key)
    {
        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }

    protected function defaultJsonHeaders(): array {
        return[
            'accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    protected function userForAuth()
    {
        if (User::count() == 0) {
            return User::factory()->create(1);
        } else {
            return User::all()->first();
        }
    }

}

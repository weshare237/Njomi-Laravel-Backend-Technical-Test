<?php

namespace App\Contracts;

interface ITransactionService
{

    public function transfer(string $sourceAccountNo, string $destinationAccountNo, float $amount, float $charges = 0.0, string $comments = '');

    public function deposit(String $accountNo, float $amount, float $charges = 0.0, string $comments = '');

    public function withdraw(String $accountNo, float $amount, float $charges = 0.0, string $comments = '');
}

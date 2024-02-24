<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientAccountBalanceException extends Exception
{

    private $accountNo;
    private $accountBalance = 0.0;

    /**
     * @param String $accountNo
     * @param float $accountBalance
     */
    public function __construct(string $accountNo, float $accountBalance)
    {
        $this->accountNo = $accountNo;
        $this->accountBalance = $accountBalance;
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'details' => [
                'account_number' => $this->accountNo,
                'account_balance' => $this->accountBalance
            ],
            'code' => 'ERR_100'
        ], 400);
    }

}

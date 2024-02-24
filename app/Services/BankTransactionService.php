<?php

namespace App\Services;

use App\Contracts\ITransactionService;
use App\Exceptions\InsufficientAccountBalanceException;
use App\Models\BankAccount;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class BankTransactionService implements ITransactionService
{

    /**
     * Transfer funds between accounts.
     *
     * @param string $sourceAccountNo
     * @param string $destinationAccountNo
     * @param float $amount
     * @param float $charges
     * @param string $comments
     * @return TransactionHistory
     * @throws InsufficientAccountBalanceException|Throwable
     */
    public function transfer(string $sourceAccountNo, string $destinationAccountNo, float $amount, float $charges = 0.0, string $comments = ''): TransactionHistory
    {
        $source = BankAccount::where('account_number', $sourceAccountNo)->first();
        $destination = BankAccount::where('account_number', $destinationAccountNo)->first();

        if ($source == null || $destination == null) {
            throw new NotFoundHttpException();
        }

        $amountTransferable = $amount + $charges;
        if ($source->account_balance <= $amountTransferable) {
            Log::info('Insufficient account balance. Failed to transfer ' . $amount . ' from ' . $source->account_number . ' to ' . $destination->account_number);
            throw new InsufficientAccountBalanceException($source->account_number, $source->account_balance);
        }

        try {
            return DB::transaction(function () use ($source, $destination, $amount, $charges, $amountTransferable, $comments) {

                $source->account_balance = $source->account_balance - $amountTransferable;
                $source->updated_at = Carbon::now();
                $destination->account_balance = $destination->account_balance + $amount;
                $destination->updated_at = Carbon::now();
                $destination->save();

                $reference = Str::uuid()->toString();

                $debitTransaction = new TransactionHistory();
                $debitTransaction->bank_account_id = $source->id;
                $debitTransaction->transaction_type = 'TRANSFER';
                $debitTransaction->transaction_action = 'DEBIT';
                $debitTransaction->reference = $reference;
                $debitTransaction->amount = $amount;
                $debitTransaction->charges = $charges;
                $debitTransaction->total_transaction_amount = -$amountTransferable;
                $debitTransaction->account_balance_after_transaction = $source->account_balance;
                $debitTransaction->targeted_external_bank_account_id = $destination->id;
                $debitTransaction->comments = $comments;
                $debitTransaction->created_at = Carbon::now();
                $debitTransaction->updated_at = Carbon::now();
                $debitTransaction->save();

                $creditTransaction = new TransactionHistory();
                $creditTransaction->bank_account_id = $source->id;
                $creditTransaction->transaction_type = 'DEPOSIT';
                $creditTransaction->transaction_action = 'CREDIT';
                $creditTransaction->reference = $reference;
                $creditTransaction->amount = $amount;
                $creditTransaction->charges = 0.0;
                $creditTransaction->total_transaction_amount = $amount;
                $creditTransaction->account_balance_after_transaction = $destination->account_balance;
                $creditTransaction->targeted_external_bank_account_id = $source->id;
                $creditTransaction->comments = $comments;
                $creditTransaction->created_at = Carbon::now();
                $creditTransaction->updated_at = Carbon::now();
                $creditTransaction->save();

                Log::info('Successfully transferred ' . $amount . ' from ' . $source->account_number . ' to ' . $destination->account_number);

                return $debitTransaction;
            });
        } catch (Throwable $e) {
            Log::error('Failed to transfer ' . $amount . ' from ' . $source->account_number . ' to ' . $destination->account_number);
            throw $e;
        }
    }

    /**
     * Deposit funds into an account.
     *
     * @param string $accountNo
     * @param float $amount
     * @param float $charges
     * @param string $comments
     * @return TransactionHistory
     * @throws Throwable
     */
    public function deposit(string $accountNo, float $amount, float $charges = 0.0, string $comments = ''): TransactionHistory
    {
        $account = BankAccount::where('account_number', $accountNo)->first();

        if ($account == null) {
            throw new NotFoundHttpException();
        }

        try {
            return DB::transaction(function () use ($account, $amount, $charges, $comments) {
                $account->account_balance = $account->account_balance + $amount;
                $account->updated_at = Carbon::now();
                $account->save();

                $reference = Str::uuid()->toString();

                $transactionHistory = new TransactionHistory();
                $transactionHistory->bank_account_id = $account->id;
                $transactionHistory->transaction_type = 'DEPOSIT';
                $transactionHistory->transaction_action = 'CREDIT';
                $transactionHistory->reference = $reference;
                $transactionHistory->amount = $amount;
                $transactionHistory->charges = $charges;
                $transactionHistory->total_transaction_amount = $amount + $charges;
                $transactionHistory->account_balance_after_transaction = $account->account_balance;
                $transactionHistory->comments = $comments;
                $transactionHistory->created_at = Carbon::now();
                $transactionHistory->updated_at = Carbon::now();
                $transactionHistory->save();

                Log::info('Successfully deposited ' . $amount . ' to ' . $account->account_number);

                return $transactionHistory;
            });
        } catch (Throwable $e) {
            Log::error('Failed to deposit ' . $amount . ' to ' . $accountNo);
            throw $e;
        }
    }

    /**
     * Withdraw funds from an account.
     *
     * @param string $accountNo
     * @param float $amount
     * @param float $charges
     * @param string $comments
     * @return TransactionHistory
     * @throws InsufficientAccountBalanceException|Throwable
     */
    public function withdraw(string $accountNo, float $amount, float $charges = 0.0, string $comments = ''): TransactionHistory
    {
        $account = BankAccount::where('account_number', $accountNo)->first();

        if ($account == null) {
            throw new NotFoundHttpException();
        }

        $amountTransferable = $amount + $charges;
        if ($account->account_balance <= $amountTransferable) {
            Log::info('Insufficient account balance. Could not withdraw ' . $amount . ' from ' . $account->account_number);
            throw new InsufficientAccountBalanceException($account->account_number, $account->account_balance);
        }

        try {
            return DB::transaction(function () use ($account, $amount, $charges, $amountTransferable, $comments) {
                $account->account_balance = $account->account_balance - $amountTransferable;
                $account->updated_at = Carbon::now();
                $account->save();

                $reference = Str::uuid()->toString();

                $transactionHistory = new TransactionHistory();
                $transactionHistory->bank_account_id = $account->id;
                $transactionHistory->transaction_type = 'WITHDRAWAL';
                $transactionHistory->transaction_action = 'DEBIT';
                $transactionHistory->reference = $reference;
                $transactionHistory->amount = $amount;
                $transactionHistory->charges = $charges;
                $transactionHistory->total_transaction_amount = -$amountTransferable;
                $transactionHistory->account_balance_after_transaction = $account->account_balance;
                $transactionHistory->comments = $comments;
                $transactionHistory->created_at = Carbon::now();
                $transactionHistory->updated_at = Carbon::now();
                $transactionHistory->save();

                Log::info('Successfully withdrew ' . $amount . ' from ' . $account->account_number);

                return $transactionHistory;
            });
        } catch (Throwable $e) {
            Log::error('Failed to withdraw ' . $amount . ' from ' . $accountNo);
            throw $e;
        }
    }
}

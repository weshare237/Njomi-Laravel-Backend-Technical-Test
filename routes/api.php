<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankBranchController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 *
 * These routes are responsible for handling API requests within the application.
 * They are loaded by the RouteServiceProvider within the 'api' middleware group.
 * Enjoy building your API!
 */
Route::prefix('v1')->group(function () {

    // Users Routes
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/random', [UserController::class, 'findRandomUser'])->name('random');
        Route::get('/random-access-token', [UserController::class, 'userToken'])->name('tokens');
    });

    // Authenticated Routes
    Route::middleware('auth:sanctum')->group(function () {

        // User Routes
        Route::get('/users.me', [UserController::class, 'me'])->name('users.me');

        // Bank Routes
        Route::resource('banks', BankController::class)->only([
            'index', 'show', 'update'
        ]);
        Route::get('banks/{bank}/bank-branches', [BankController::class, 'bankBranches']);

        // Bank Branch Routes
        Route::resource('bank-branches', BankBranchController::class)->only([
            'index', 'store', 'show', 'update'
        ]);

        // Bank Transaction Routes
        Route::prefix('bank-transactions')->name('transactions.')->group(function () {
            Route::post('/transfer', [BankTransactionController::class, 'transfer'])->name('transfer');
            Route::post('/deposit', [BankTransactionController::class, 'deposit'])->name('deposit');
            Route::post('/withdraw', [BankTransactionController::class, 'withdraw'])->name('withdraw');
            Route::get('', [TransactionHistoryController::class, 'index'])->name('index');
            Route::get('/{bank_transaction}', [TransactionHistoryController::class, 'show'])->name('show');
        });

        // Bank Account Routes
        Route::prefix('bank-accounts')->name('bank-accounts.')->group(function () {
            Route::post('', [BankAccountController::class, 'store'])->name('store');
            Route::get('', [BankAccountController::class, 'index'])->name('index');
            Route::get('/{bank_account}', [BankAccountController::class, 'show'])->name('show');
            Route::get('/{bank_account}/transfer-histories', [BankAccountController::class, 'transferHistories'])->name('transfer-histories');
            Route::get('/{bank_account}/account-balance', [BankAccountController::class, 'accountBalance'])->name('account-balance');
        });

        // Customer Routes
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('', [CustomerController::class, 'index'])->name('index');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        });
    });
});

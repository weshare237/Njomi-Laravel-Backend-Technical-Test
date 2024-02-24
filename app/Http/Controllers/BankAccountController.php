<?php

namespace App\Http\Controllers;

use App\Contracts\ITransactionService;
use App\Http\Resources\BankAccountCollection;
use App\Http\Resources\BankAccountResource;
use App\Http\Resources\TransactionHistoryCollection;
use App\Models\BankAccount;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;


/**
 * @group Bank Account Management
 * @authenticated
 *
 * The API to management the bank account resources.
 */
class BankAccountController extends Controller
{

    private $transactionService;

    /**
     * @param ITransactionService $transactionService
     */
    public function __construct(ITransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Get banks accounts
     *
     * This resource fetches all the list of bank accounts.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\BankAccountCollection
     * @apiResourceModel App\Models\BankAccount
     *
     * @param Request $request
     * @return BankAccountCollection
     */
    public function index(Request $request): BankAccountCollection
    {
        return new BankAccountCollection(BankAccount::paginate($request->page_size ?? 20));
    }

    /**
     * Create bank account
     *
     * This endpoint creates a new bank account.
     *
     * @apiResource App\Http\Resources\BankAccountResource
     * @apiResourceModel App\Models\BankAccount
     *
     * @param Request $request
     * @return JsonResponse|BankAccountResource
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_type' => [
                'required',
                Rule::in(['CURRENT', 'SAVINGS'])
            ],
            'initial_deposit' => 'required|min:0',
            'customer_id' => 'required|exists:App\Models\Customer,id',
            'created_at_branch' => 'integer|required|exists:App\Models\BankBranch,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {

            try {
                return DB::transaction(function () use ($request, $validator) {
                    $validated = $validator->validated();
                    $account = new BankAccount();
                    $user = $request->user();

                    $account->account_number = Str::uuid()->toString();
                    $account->account_type = $validated['account_type'];
                    $account->account_balance = $validated['initial_deposit'];
                    $account->customer_id = $validated['customer_id'];
                    $account->created_at_branch = $validated['created_at_branch'];
                    $account->created_by = $user != null ? $user->id : null;
                    $account->is_active = true;
                    $account->created_at = Carbon::now();
                    $account->updated_at = Carbon::now();
                    $account->save();

                    $this->transactionService->deposit($account->account_number, $account->account_balance, 0.0, 'Initial deposit');

                    Log::info('Bank account, ' . $account->account_number . ' was successfully created!');
                    return new BankAccountResource($account);
                });
            } catch (Throwable $e) {
                Log::error('Failed to create account');
                throw $e;
            }
        }
    }

    /**
     * Find a bank account
     *
     * This endpoint fetches a bank account by id.
     *
     * @urlParam bank_account int required The bank account id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\BankAccountResource
     * @apiResourceModel App\Models\BankAccount
     *
     * @param int $id
     * @return BankAccountResource
     */
    public function show(int $id): BankAccountResource
    {
        return new BankAccountResource(BankAccount::findOrFail($id));
    }

    /**
     * Get bank account balance
     *
     * The current balance of the bank account will be returned.
     *
     * @urlParam bank_account int required The bank account id. Default 1. Example: 1
     *
     * @respone 200 {
     *  "account_balance": 55700.0
     * }
     *
     * @param int $id
     * @return JsonResponse
     */
    public function accountBalance(int $id): JsonResponse
    {
        $account = BankAccount::findOrFail($id);
        return response()->json(['account_balance' => $account->account_balance], 200);
    }

    /**
     * Display transfer histories
     *
     * Fetches all transfer histories. Note that deposits and withdrawal are not included.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @urlParam bank_account int required The bank account id. Default 1. Example: 1.
     *
     * @apiResourceCollection App\Http\Resources\TransactionHistoryCollection
     * @apiResourceModel App\Models\TransactionHistory
     *
     * @param Request $request
     * @param int $id
     * @return TransactionHistoryCollection
     */
    public function transferHistories(Request $request, int $id): TransactionHistoryCollection
    {
        $histories = TransactionHistory::where(['bank_account_id', '=', $id])
            ->where('transaction_type', '=', 'TRANSFER')
            ->paginate($request->page_size ?? 20);
        return new TransactionHistoryCollection($histories);
    }
}

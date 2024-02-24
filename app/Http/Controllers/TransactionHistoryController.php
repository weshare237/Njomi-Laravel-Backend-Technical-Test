<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionHistoryCollection;
use App\Http\Resources\TransactionHistoryResource;
use App\Models\TransactionHistory;
use Illuminate\Http\Request;

/**
 * @group Transaction History
 * @authenticated
 *
 * The API displays bank transactions.
 */
class TransactionHistoryController extends Controller
{
    /**
     * Get all transactions
     *
     * This resource fetches all the list of transactions.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\TransactionHistoryCollection
     * @apiResourceModel App\Models\TransactionHistory
     *
     * @param Request $request
     * @return TransactionHistoryCollection
     */
    public function index(Request $request): TransactionHistoryCollection
    {
        return new TransactionHistoryCollection(TransactionHistory::paginate($request->page_size ?? 20));
    }

    /**
     * Find a transaction history
     *
     * This endpoint fetches a transaction history by id.
     *
     * @urlParam transaction_history int required The transaction history id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\TransactionHistoryResource
     * @apiResourceModel App\Models\TransactionHistory
     *
     *
     * @param  int  $id
     * @return TransactionHistoryResource
     */
    public function show(int $id): TransactionHistoryResource
    {
        return new TransactionHistoryResource(TransactionHistory::findOrFail($id));
    }

}

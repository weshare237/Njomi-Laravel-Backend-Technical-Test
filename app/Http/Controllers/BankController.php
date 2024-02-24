<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankBranchCollection;
use App\Http\Resources\BankCollection;
use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\BankBranch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @group Bank Management
 * @authenticated
 *
 * The API to perform simple management tasks on the bank info.
 */
class BankController extends Controller
{
    /**
     * Get banks branches
     *
     * This resource fetches all the list of bank accounts.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\BankCollection
     * @apiResourceModel App\Models\Bank
     *
     * @param Request $request
     * @return BankCollection
     */
    public function index(Request $request): BankCollection
    {
        return new BankCollection(Bank::paginate($request->page_size ?? 20));
    }

    /**
     * Find a bank
     *
     * This endpoint fetches a bank by id.
     *
     * @urlParam bank int required The bank branch id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\BankResource
     * @apiResourceModel App\Models\Bank
     *
     * @param int $id
     * @return BankResource
     */
    public function show(int $id): BankResource
    {
        return new BankResource(Bank::findOrFail($id));
    }

    /**
     * Update bank
     *
     * This endpoint updates bank.
     *
     * @urlParam bank int required The bank branch id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\BankResource
     * @apiResourceModel App\Models\Bank
     *
     * @param Request $request
     * @param int $id
     * @return BankResource|JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:128',
            'code' => 'required|max:16'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $bank = Bank::findOrFail($id);
            $validated = $validator->validated();
            $bank->name = $validated['name'];
            $bank->code = $validated['code'];
            $bank->updated_at = Carbon::now();
            $bank->save();
            return new BankResource($bank);
        }
    }

    /**
     * Get branches of a bank
     *
     * This resource fetches the list of branches in a bank.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @urlParam bank int required The bank branch id. Default 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\BankBranchCollection
     * @apiResourceModel App\Models\BankBranch
     *
     * @param Request $request
     * @param int $id
     * @return BankBranchCollection
     */
    public function bankBranches(Request $request, int $id): BankBranchCollection
    {
        return new BankBranchCollection(BankBranch::where(['bank_id', '=', $id])->paginate($request->page_size ?? 20));
    }

}

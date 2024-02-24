<?php

namespace App\Http\Controllers;

use App\Http\Resources\BankBranchCollection;
use App\Http\Resources\BankBranchResource;
use App\Models\BankBranch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/**
 * @group Bank Branches Management
 * @authenticated
 *
 * The API to manage bank branches.
 */
class BankBranchController extends Controller
{
    /**
     * Get banks branches
     *
     * This resource fetches all the list of bank accounts.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\BankBranchCollection
     * @apiResourceModel App\Models\BankBranch
     *
     * @param Request $request
     * @return BankBranchCollection
     */
    public function index(Request $request): BankBranchCollection
    {
        return new BankBranchCollection(BankBranch::paginate($request->page_size ?? 20));
    }

    /**
     * Create bank branch
     *
     * This endpoint creates a new bank branch.
     *
     * @apiResource App\Http\Resources\BankAccountResource
     * @apiResourceModel App\Models\BankAccount
     *
     * @param Request $request
     * @return BankBranchResource|JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:128',
            'address_line_1' => 'required|min:0|max:64',
            'address_line_2' => 'required|min:0|max:64',
            'state' => 'nullable|min:3|max:3',
            'country' => 'nullable|min:3|max:3',
            'bank' => 'required|exists:App\Models\Bank,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $validated = $validator->validated();
            $branch = new BankBranch();
            $branch->name = $validated['name'];
            $branch->address_line_1 = $validated['address_line_1'];
            $branch->address_line_2 = $validated['address_line_2'];
            $branch->state = $validated['state'];
            $branch->country = $validated['country'];
            $branch->bank_id = $validated['bank'];
            $branch->created_at = Carbon::now();
            $branch->updated_at = Carbon::now();
            $branch->save();
            return new BankBranchResource($branch);
        }
    }

    /**
     * Find a bank branch
     *
     * This endpoint fetches a bank branch by id.
     *
     * @urlParam bank_branch int required The bank branch id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\BankBranchResource
     * @apiResourceModel App\Models\BankBranch
     *
     * @param int $id
     * @return BankBranchResource
     */
    public function show(int $id): BankBranchResource
    {
        return new BankBranchResource(BankBranch::findOrFail($id));
    }

    /**
     * Update bank branch
     *
     * This endpoint updates bank branch.
     *
     * @urlParam bank_branch int required The bank branch id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\BankAccountResource
     * @apiResourceModel App\Models\BankAccount
     *
     * @param Request $request
     * @param int $id
     * @return BankBranchResource|JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $branch = BankBranch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:0|max:128',
            'address_line_1' => 'required|min:0|max:64',
            'address_line_2' => 'required|min:0|max:64',
            'state' => 'nullable|min:3|max:3',
            'country' => 'nullable|min:3|max:3',
            'bank' => 'required|exists:App\Models\BankBranch,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $validated = $validator->validated();

            $branch->name = $validated['name'];
            $branch->address_line_1 = $validated['address_line_1'];
            $branch->address_line_2 = $validated['address_line_2'];
            $branch->state = $validated['state'];
            $branch->country = $validated['country'];
            $branch->updated_at = Carbon::now();
            $branch->save();
            return new BankBranchResource($branch);
        }
    }

}

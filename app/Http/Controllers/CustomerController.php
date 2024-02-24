<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


/**
 * @group Customer Management
 * @authenticated
 *
 * The API helps in managing customers.
 */
class CustomerController extends Controller
{
    /**
     * Get customers
     *
     * This resource fetches all customers.
     *
     * @queryParam page_size int Size per page. Default is 20. Example: 20
     * @queryParam page int Page to view. Default is 1. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\BankAccountCollection
     * @apiResourceModel App\Models\BankAccount
     *
     * @param Request $request
     * @return CustomerCollection
     */
    public function index(Request $request): CustomerCollection
    {
        return new CustomerCollection(Customer::paginate($request->page_size ?? 20));
    }

    /**
     * Create customer
     *
     * This endpoint creates a customer.
     *
     * @apiResource App\Http\Resources\CustomerResource
     * @apiResourceModel App\Models\Customer
     *
     * @param Request $request
     * @return CustomerResource|JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:0|max:64',
            'middle_name' => 'nullable|min:0|max:64',
            'last_name' => 'required|min:0|max:64',
            'account_type' => [
                'nullable',
                Rule::in(['M', 'F'])
            ],
            'date_of_birth' => 'required',
            'place_of_birth' => 'required|max:64',
            'nationality' => 'required|max:3',
            'country_of_origin' => 'required|max:3',
            'address_line_1' => 'required|min:0|max:64',
            'address_line_2' => 'required|min:0|max:64',
            'phone' => 'nullable|max:32',
            'email' => 'nullable|max:64',
            'branch' => 'required|exists:App\Models\BankBranch,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $validated = $validator->validated();
            $customer = new Customer();
            $user = $request->user();


            $customer->first_name = $validated['first_name'];
            $customer->middle_name = $validated['middle_name'];
            $customer->last_name = $validated['last_name'];
            $customer->sex = $validated['sex'];
            $customer->date_of_birth = $validated['date_of_birth'];
            $customer->place_of_birth = $validated['place_of_birth'];
            $customer->nationality = $validated['nationality'];
            $customer->country_of_origin = $validated['country_of_origin'];
            $customer->address_line_1 = $validated['address_line_1'];
            $customer->address_line_2 = $validated['address_line_2'];
            $customer->phone = $validated['phone'];
            $customer->email = $validated['email'];
            $customer->created_at_branch = $validated['branch'];
            $customer->created_by = $user->id;
            $customer->created_at = Carbon::now();
            $customer->updated_at = Carbon::now();
            $customer->save();
            return new CustomerResource($customer);
        }
    }

    /**
     * Find a customer
     *
     * This endpoint fetches a customer by id.
     *
     * @urlParam customer int required The customer id. Default 1. Example: 1
     *
     * @apiResource App\Http\Resources\CustomerResource
     * @apiResourceModel App\Models\Customer
     *
     * @param int $id
     * @return CustomerResource
     */
    public function show(int $id): CustomerResource
    {
        return new CustomerResource(Customer::findOrFail($id));
    }

}

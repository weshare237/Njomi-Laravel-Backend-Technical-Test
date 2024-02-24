<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;


/**
 * @group Auth - User management
 *
 * The API to perform simple user management.
 */
class UserController extends Controller
{

    /**
     * @param Request $request
     * @return UserResource
     */
    public function findRandomUser(Request $request): UserResource
    {
        return new UserResource(User::all()->firstOrFail());
    }

    /**
     * Login
     * Get Random Access Token
     *
     * The generates a random access token for a random user.
     *
     * @respone 200 {
     *  "token": "XXX-xxx-XXX"
     * }
     *
     * @return JsonResponse
     */
    public function userToken(): JsonResponse
    {
        $user = User::all()->first();
        if ($user == null) {
            $user = User::factory()->count(1)->create();
        }
        $token = $user->createToken(Str::uuid()->toString());
        return response()->json(['token' => $token->plainTextToken], 200);
    }

    /**
     * Get Me
     *
     * Fetches the details of the currently authenticated user
     * @authenticated
     *
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     *
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

}

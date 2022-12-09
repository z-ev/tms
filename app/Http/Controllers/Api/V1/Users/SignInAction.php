<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Requests\SignInRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Exceptions\JsonApiException;
use LaravelJsonApi\Core\Responses\MetaResponse;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use Symfony\Component\HttpFoundation\Response;

class SignInAction extends JsonApiController
{
    /**
     * @param SignInRequest $request
     *
     * @throws JsonApiException
     *
     * @return MetaResponse
     *
     * @OA\POST (
     *   path="/api/v1/users/actions/signin",
     *   summary="Retrieve your token to use the other endpoints.",
     *   tags={"Users"},
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="email",
     *                      description="email",
     *                      type="string",
     *                 ),
     *                 @OA\Property(
     *                      description="password",
     *                      property="password",
     *                      type="string",
     *                 ),
     *                 example={
     *                  "email"= "admin@admin.ru",
     *                  "password"= "password",
     *                 }
     *              ),
     *             ),
     *         ),
     * @OA\Response(
     *      response=200,
     *       description="Success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="The username or password you entered is incorrect"
     *   )
     *  )
     */
    public function __invoke(
        SignInRequest $request
    ): MetaResponse {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw JsonApiException::error([
                'status' => Response::HTTP_UNAUTHORIZED,
                'detail' => __('toms.users.incorrect'),
            ]);
        }

        $user = User::where('email', $request->email ?? '')->first();

        return MetaResponse::make([
            'user'  => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('MyToken', [$user->roles])->plainTextToken,
        ]);
    }
}

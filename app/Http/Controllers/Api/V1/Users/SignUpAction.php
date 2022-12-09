<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use LaravelJsonApi\Core\Responses\MetaResponse;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

class SignUpAction extends JsonApiController
{
    /**
     * @param SignUpRequest $request
     *
     * @return MetaResponse
     *
     * @OA\POST (
     *   path="/api/v1/users/actions/signup",
     *   summary="Crete user and get token to use the other endpoints.",
     *   tags={"Users"},
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      description="name",
     *                      type="string",
     *                 ),
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
     *                  @OA\Property(
     *                      description="roles",
     *                      property="roles",
     *                      type="array",
     * @OA\Items(
     *                      type="string",
     *                      example="ROLE_TOMS_CLIENT"
     *                  ),
     *                 ),
     *                 example={
     *                  "name"="Test",
     *                  "email"= "test@test.ru",
     *                  "password"= "password",
     *                  "roles"= {"ROLE_TOMS_CLIENT"}
     *                 }
     *                 ),
     *             ),
     *         ),
     * @OA\Response(
     *      response=200,
     *       description="Success",
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Unprocessable Entity"
     *   )
     *  )
     */
    public function __invoke(
        SignUpRequest $request
    ): MetaResponse {
        $data = $request->all();

        $data['password'] = Hash::make($data['password']);

        if (!array_key_exists('roles', $data) || !User::isValidRoles($data['roles'] ?? [])) {
            $data['roles'] = [User::ROLE_CLIENT];
        }

        $roles               = $data['roles'];
        $data['roles']       = json_encode($data['roles']);
        $data['merchant_id'] = $data['merchantId'];
        $data['status_id']   = $data['statusId'];
        $user                = User::create($data);

        return MetaResponse::make([
            'token' => $user->createToken('MyToken', [$roles])->plainTextToken,
        ]);
    }
}

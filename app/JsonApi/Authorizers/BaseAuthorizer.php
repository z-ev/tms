<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\Authorizers;

use App\JsonApi\Authorizers\Traits\AuthTrait;
use App\Models\User;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Contracts\Auth\Authorizer;

class BaseAuthorizer implements Authorizer
{
    use AuthTrait;
    /**
     * @var User|null
     */
    protected ?User $user;
    protected string $mainRole;

    /**
     * @var Repository|Application|mixed
     */
    protected mixed $permissions;

    public function __construct()
    {
        $this->user        = Auth::user();
        $this->mainRole    = User::getMainRole($this->user->roles);
        $this->permissions = config('toms-permissions.' . $this->mainRole . '.permissions');
    }

    /**
     * Authorize the index controller action.
     *
     * @param Request $request
     * @param string  $modelClass
     *
     * @return bool
     */
    public function index(Request $request, string $modelClass): bool
    {
        return $this->user->merchant_id !== null;
    }

    /**
     * Authorize the store controller action.
     *
     * @param Request $request
     * @param string  $modelClass
     *
     * @return bool
     */
    public function store(Request $request, string $modelClass): bool
    {
        $contentMerchantId = null;
        $attributes        = json_decode($request->getContent())->data->attributes;

        if (property_exists($attributes, 'merchantId')) {
            $contentMerchantId = $attributes->merchantId;
        }

        return $this->user->isAdministrator()
            || (
                $this->user->merchant_id !== null
                && $this->user->merchant_id === $contentMerchantId
            );
    }

    /**
     * Authorize the show controller action.
     *
     * @param Request $request
     * @param object  $model
     *
     * @return bool
     */
    public function show(Request $request, object $model): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the update controller action.
     *
     * @param object  $model
     * @param Request $request
     *
     * @return bool
     */
    public function update(Request $request, object $model): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the destroy controller action.
     *
     * @param Request $request
     * @param object  $model
     *
     * @return bool
     */
    public function destroy(Request $request, object $model): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the show-related and show-relationship controller action.
     *
     * @param Request $request
     * @param object  $model
     * @param string  $fieldName
     *
     * @return bool
     */
    public function showRelationship(Request $request, object $model, string $fieldName): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the update-relationship controller action.
     *
     * @param Request $request
     * @param object  $model
     * @param string  $fieldName
     *
     * @return bool
     */
    public function updateRelationship(Request $request, object $model, string $fieldName): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the attach-relationship controller action.
     *
     * @param Request $request
     * @param object  $model
     * @param string  $fieldName
     *
     * @return bool
     */
    public function attachRelationship(Request $request, object $model, string $fieldName): bool
    {
        return $this->checkPerms($request, $model);
    }

    /**
     * Authorize the detach-relationship controller action.
     *
     * @param Request $request
     * @param object  $model
     * @param string  $fieldName
     *
     * @return bool
     */
    public function detachRelationship(Request $request, object $model, string $fieldName): bool
    {
        return $this->checkPerms($request, $model);
    }

    public function showRelated(Request $request, object $model, string $fieldName): bool
    {
        return $this->checkPerms($request, $model);
    }
}

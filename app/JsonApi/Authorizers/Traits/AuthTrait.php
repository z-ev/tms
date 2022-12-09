<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\Authorizers\Traits;

use Illuminate\Http\Request;
use LaravelJsonApi\Laravel\Routing\Route;

trait AuthTrait
{
    /**
     * @param Request $request
     * @param object  $model
     *
     * @return bool
     */
    public function checkPerms(Request $request, object $model): bool
    {
        $route = $request->route();

        $resource = $route->defaults[Route::RESOURCE_RELATIONSHIP]
            ?? $route->defaults[Route::RESOURCE_TYPE]
            ?? null;

        $action = explode('.', $route->action['as']);
        $action = end($action);

        if (!array_key_exists($resource, $this->permissions)
            || !array_key_exists($action, $this->permissions[$resource])) {
            return false;
        }

        if ($this->permissions[$resource][$action] == 'own') {
            return $this->ownSupplier($model);
        }

        if ($this->permissions[$resource][$action] == 'all') {
            return true;
        }

        return false;
    }

    /**
     * @param object $model
     *
     * @return bool
     */
    public function ownSupplier(object $model): bool
    {
        return $this->user->isAdministrator()
            || ($this->user->merchant_id !== null
                && $this->user->merchant_id === $model->merchant_id);
    }
}

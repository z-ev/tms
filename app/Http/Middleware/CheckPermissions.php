<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Contracts\Schema\Filter;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Core\Facades\JsonApi;
use LaravelJsonApi\Laravel\Routing\Route;
use LogicException;

class CheckPermissions
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @throws AuthorizationException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user     = Auth::user();
        $mainRole = User::getMainRole($user->roles);

        $route = $request->route();

        $resource = $route->defaults[Route::RESOURCE_RELATIONSHIP]
            ?? $route->defaults[Route::RESOURCE_TYPE]
            ?? null;

        if ($resource === null) {
            return $next($request);
        }

        $action = explode('.', $route->action['as']);
        $action = end($action);

        $permissions = config('toms-permissions.' . $mainRole . '.permissions');

        if (!array_key_exists($resource, $permissions) || !array_key_exists($action, $permissions[$resource])) {
            throw new AuthorizationException();
        }

        if ($permissions[$resource][$action] == 'all') {
            return $next($request);
        }

        $merchantId = $user->merchant_id;
        $server     = JsonApi::server('v1');

        $schema = $this->getSchema($server, $resource);

        if ($action == 'index' || $action == 'show') {
            $merchantFilter = current(array_filter(
                $schema->filters(),
                static fn (Filter $filter) => $filter->key() === 'merchantId'
            ));

            $filter = [];

            if (array_key_exists('filter', $request->query->all())) {
                $filter = $request->query->all('filter');
            }

            if ($merchantFilter !== false) {
                $filter['merchantId'] = $merchantId;
            }

            if (count($filter) > 0) {
                $request->query->set('filter', $filter);
            }
        }

        return $next($request);
    }

    /**
     * @param Server $server
     * @param string $resource
     *
     * @return mixed
     */
    protected function getSchema(Server $server, string $resource)
    {
        try {
            $schema = $server->schemas()->schemaFor($resource);
        } catch (LogicException) {
            $resource = strtolower(preg_replace('/(?<!\ )[A-Z]/', '-$0', $resource));
            $schema   = $server->schemas()->schemaFor($resource);
        }

        return $schema;
    }
}

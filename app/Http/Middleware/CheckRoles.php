<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Exceptions\JsonApiException;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param Request    $request
     * @param Closure    $next
     * @param int|string $maxAttempts
     * @param float|int  $decayMinutes
     * @param string     $prefix
     *
     * @throws ThrottleRequestsException
     * @throws JsonApiException
     *
     * @return Response
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user && $user->status_id == User::STATUS_BAN) {
            throw JsonApiException::error([
                'status' => Response::HTTP_UNAUTHORIZED,
                'detail' => __('toms.users.ban'),
            ]);
        }

        $mainRole = User::ROLE_GUEST;

        if ($user && $user->roles) {
            $mainRole = User::getMainRole($user->roles);
        }

        $throttle = config('toms-permissions.' . $mainRole . '.throttle');

        if ($throttle) {
            $maxAttempts  = $throttle['maxAttempts'];
            $decayMinutes = $throttle['decayMinutes'];
        }

        if (is_string($maxAttempts)
            && func_num_args() === 3
            && !is_null($limiter = $this->limiter->limiter($maxAttempts))
        ) {
            return $this->handleRequestUsingNamedLimiter($request, $next, $maxAttempts, $limiter);
        }

        return $this->handleRequest(
            $request,
            $next,
            [
                (object) [
                    'key'              => $prefix . $this->resolveRequestSignature($request),
                    'maxAttempts'      => $this->resolveMaxAttempts($request, $maxAttempts),
                    'decayMinutes'     => $decayMinutes,
                    'responseCallback' => null,
                ],
            ]
        );
    }
}

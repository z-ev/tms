<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\V1\Orders;

use App\JsonApi\Authorizers\BaseAuthorizer;
use Illuminate\Http\Request;

class OrderAuthorizer extends BaseAuthorizer
{
    /**
     * @param Request $request
     * @param object  $model
     *
     * @return bool
     */
    public function acceptedOrder(Request $request, object $model): bool
    {
        return $this->checkPerms($request, $model);
    }
}

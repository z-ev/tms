<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\Query;

use App\JsonApi\Authorizers\Traits\AuthTrait;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class ActionsQuery extends ResourceQuery
{
    use AuthTrait;

    /**
     * Authorize the request.
     *
     * @return bool|null
     */
    public function authorize(): ?bool
    {
        if ($this->is(
            '*actions/update-status',
        ) && $this->model() !== null) {
            return $this->checkPerms($this, $this->model());
        }

        return null;
    }
}

<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\V1;

use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

abstract class BaseSchema extends Schema
{
    /**
     * @var array<string,int>|null
     */
    protected ?array $defaultPagination = ['number' => 1];

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make()
            ->withDefaultPerPage(10);
    }

    /**
     * TODO: REMOVE.
     */
    public function authorizable(): bool
    {
        return false;
    }
}

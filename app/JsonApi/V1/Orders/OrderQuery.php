<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\V1\Orders;

use App\JsonApi\Query\ActionsQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class OrderQuery extends ActionsQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'fields' => [
                'nullable',
                'array',
                JsonApiRule::fieldSets(),
            ],
            'filter' => [
                'nullable',
                'array',
                JsonApiRule::filter()->forget('id'),
            ],
            'include' => [
                'nullable',
                'string',
                JsonApiRule::includePaths(),
            ],
            'page'      => JsonApiRule::notSupported(),
            'sort'      => JsonApiRule::notSupported(),
            'withCount' => [
                'nullable',
                'string',
                JsonApiRule::countable(),
            ],
        ];
    }
}

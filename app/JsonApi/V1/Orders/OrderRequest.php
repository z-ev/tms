<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\V1\Orders;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class OrderRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'id'          => ['required', JsonApiRule::clientId()],
            'number'      => ['required', 'string'],
            'address'     => ['required', 'string'],
            'description' => ['string'],

            'merchantId' => ['required', 'uuid'],

            'statusId' => ['required', 'integer'],
            'userId'   => ['required', 'integer'],

            'weight' => ['numeric'],

            'totalAmount'           => ['required', 'integer'],
            'totalAmountWithoutVat' => ['integer'],
            'totalVatAmount'        => ['integer'],

            'currency' => ['string'],
            'items'    => ['array'],

            'refundedAmount'           => ['integer'],
            'refundedAmountWithoutVat' => ['numeric'],
            'refundedTotalVatAmount'   => ['integer'],
            'refundedTotalQty'         => ['integer'],

            'deliveredAt' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'refundedAt'  => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}

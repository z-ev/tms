<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Orders;

use App\JsonApi\V1\Orders\OrderQuery;
use App\JsonApi\V1\Orders\OrderSchema;
use App\Models\Order;
use LaravelJsonApi\Core\Exceptions\JsonApiException;
use LaravelJsonApi\Core\Responses\MetaResponse;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

class UpdateStatusAction extends JsonApiController
{
    /**
     * @param OrderSchema $schema
     * @param OrderQuery  $request
     * @param Order       $order
     *
     * @throws JsonApiException
     *
     * @return MetaResponse
     */
    public function __invoke(
        OrderSchema $schema,
        OrderQuery $request,
        Order $order,
    ): MetaResponse {
        $request->validate([
            'statusId' => 'required|numeric',
            'reason'   => 'string',
        ]);

        $order->updateStatus($request->statusId, $request->reason ?? '');

        return MetaResponse::make([
            'orderId'  => $order->id ?? '',
            'statusId' => $order->status_id ?? '',
            'action'   => 'updateStatus',
            'success'  => true,
        ]);
    }
}

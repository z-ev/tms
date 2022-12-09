<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace App\JsonApi\V1\Orders;

use App\JsonApi\V1\BaseSchema;
use App\Models\Order;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasOne;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Filters\WhereIn;

/**
 * @OA\Get(
 ** path="/api/v1/orders",
 *   tags={"Orders"},
 *   summary="Orders",
 *   @OA\Parameter(
 *          name="filter[merchantId]",
 *          in="query",
 *          required=false,
 *          description="Filter by merchant ID",
 *          example="c34cf7a9-b5a7-376a-967a-2866dd19f1d3"
 *     ),
 *   @OA\Parameter(
 *          name="filter[userId]",
 *          in="query",
 *          required=false,
 *          description="Filter by user ID",
 *          example="1"
 *   ),
 *   @OA\Parameter(
 *          name="filter[statusId]",
 *          in="query",
 *          required=false,
 *          description="Filter by status ID",
 *          example="1"
 *   ),
 *   @OA\Response(
 *      response=200,
 *       description="Success",
 *   ),
 *   @OA\Response(
 *     response=403,
 *     description="Forbidden"
 *   )
 *)
 */
class OrderSchema extends BaseSchema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Order::class;

    /**
     * @var string[]
     */
    protected $defaultSort = ['statusId', '-createdAt'];

    /**
     * Get the resource fields.
     *
     * @return iterable<mixed>
     */
    public function fields(): iterable
    {
        return [
            ID::make()->uuid()->clientIds(),
            Str::make('number')->sortable(),
            Str::make('address')->sortable(),
            Str::make('description')->sortable(),

            Str::make('merchantId')->sortable(),

            Number::make('statusId')->sortable(),
            Number::make('userId')->sortable(),

            Number::make('weight')->sortable(),

            Number::make('totalAmount')->sortable(),
            Number::make('totalAmountWithoutVat')->sortable(),
            Number::make('totalVatAmount')->sortable(),

            Str::make('currency'),
            ArrayList::make('items'),

            Number::make('refundedAmount'),
            Number::make('refundedAmountWithoutVat'),
            Number::make('refundedTotalVatAmount'),
            Number::make('refundedTotalQty'),

            DateTime::make('deliveredAt')->sortable(),
            DateTime::make('refundedAt')->sortable(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),

            HasOne::make('user', 'user'),
            HasOne::make('merchant', 'merchant'),
            HasMany::make('statusHistories', 'statusHistories'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): iterable
    {
        return [
            WhereIdIn::make($this)->delimiter(','),
            WhereIn::make('merchantId')->delimiter(','),
            WhereIn::make('statusId')->delimiter(','),
            WhereIn::make('userId')->delimiter(','),
        ];
    }
}

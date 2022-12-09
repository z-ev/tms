<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources;

use App\Models\Order;
use Faker\Factory as Faker;
use Faker\Generator;
use Tests\Resources\Traits\AuthTrait;
use Tests\Resources\Traits\StoreTrait;

class OrderTest extends AbstractResourceTest
{
    use AuthTrait;
    use StoreTrait;

    public static string $model        = Order::class;
    public static string $resourceType = 'orders';

    /**
     * @var Generator
     */
    protected Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Faker::create();
    }

    /** @test */
    public function testIndex(): void
    {
        $this->doTestAdminIndex();
    }

    /** @test */
    public function testStore(): void
    {
        $order = self::$model::factory()->make();

        $data = [
            'type'       => self::$resourceType,
            'id'         => "{$order->id}",
            'attributes' => [
                'number'                   => $order->number,
                'address'                  => $order->address,
                'description'              => $order->description,
                'merchantId'               => $order->merchant_id,
                'statusId'                 => $order->status_id,
                'userId'                   => $order->user_id,
                'weight'                   => $order->weight,
                'totalAmount'              => $order->total_amount,
                'totalAmountWithoutVat'    => $order->total_amount_without_vat,
                'totalVatAmount'           => $order->total_vat_amount,
                'currency'                 => $order->currency,
                'items'                    => $order->items,
                'refundedAmount'           => $order->refunded_amount,
                'refundedAmountWithoutVat' => $order->refunded_amount_without_vat,
                'refundedTotalVatAmount'   => $order->refunded_total_vat_amount,
                'refundedTotalQty'         => $order->refunded_total_qty,
                'deliveredAt'              => $order->delivered_at,
                'refundedAt'               => $order->refunded_at,
            ],
        ];

        $this->storeAdminData($data);

        $this->assertDatabaseHas(self::$resourceType, [
            'number'                      => $order->number,
            'address'                     => $order->address,
            'description'                 => $order->description,
            'merchant_id'                 => $order->merchant_id,
            'status_id'                   => $order->status_id,
            'user_id'                     => $order->user_id,
            'weight'                      => $order->weight,
            'total_amount'                => $order->total_amount,
            'total_amount_without_vat'    => $order->total_amount_without_vat,
            'total_vat_amount'            => $order->total_vat_amount,
            'currency'                    => $order->currency,
            'refunded_amount'             => $order->refunded_amount,
            'refunded_amount_without_vat' => $order->refunded_amount_without_vat,
            'refunded_total_vat_amount'   => $order->refunded_total_vat_amount,
            'refunded_total_qty'          => $order->refunded_total_qty,
            'delivered_at'                => $order->delivered_at,
            'refunded_at'                 => $order->refunded_at,
        ]);
    }
}

<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Resources;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Faker\Factory as Faker;
use Faker\Generator;
use Symfony\Component\HttpFoundation\Response;
use Tests\Resources\Traits\AuthTrait;
use Tests\Resources\Traits\DestroyTrait;
use Tests\Resources\Traits\FiltersTrait;
use Tests\Resources\Traits\ShowTrait;
use Tests\Resources\Traits\StoreTrait;
use Tests\Resources\Traits\UpdateTrait;

class OrderTest extends AbstractResourceTest
{
    use AuthTrait;
    use StoreTrait;
    use ShowTrait;
    use UpdateTrait;
    use DestroyTrait;
    use FiltersTrait;

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

        $this->storeWithRoleData($data, User::ROLE_ADMIN);

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

        $data['id']                       = $this->faker->uuid();
        $data['attributes']['merchantId'] = DatabaseSeeder::MOCK_MERCHANT_ID;
        $this->storeWithRoleData($data, User::ROLE_MERCHANT);

        $data['id'] = $this->faker->uuid();
        $this->storeWithRoleData($data, User::ROLE_OPERATOR, Response::HTTP_FORBIDDEN);

        $data['id'] = $this->faker->uuid();
        $this->storeWithRoleData($data, User::ROLE_CLIENT, Response::HTTP_FORBIDDEN);

        $data['id'] = $this->faker->uuid();
        $this->storeWithRoleData($data, User::ROLE_GUEST, Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function testShow(): void
    {
        $order = $this->prepareMerchantEntity('create', self::$model, ['status_id' => Order::STATUS_PENDING['id']]);

        $expectedData = [
            'type'       => self::$resourceType,
            'id'         => "{$order->id}",
            'attributes' => [
                'number'                   => $order->number,
                'address'                  => $order->address,
                'description'              => $order->description,
                'merchantId'               => $order->merchant_id,
                'statusId'                 => $order->status_id,
                'userId'                   => $order->user_id,
                'weight'                   => number_format($order->weight, 2, '.'),
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

        $this->showWithRoleData($order, $expectedData, User::ROLE_ADMIN);

        $merchantOrder = $this->prepareMerchantEntity('create', self::$model, ['status_id' => Order::STATUS_PENDING['id']]);
        $expectedData  = [
            'type'       => self::$resourceType,
            'id'         => "{$merchantOrder->id}",
            'attributes' => [
                'number'                   => $merchantOrder->number,
                'address'                  => $merchantOrder->address,
                'description'              => $merchantOrder->description,
                'merchantId'               => $merchantOrder->merchant_id,
                'statusId'                 => $merchantOrder->status_id,
                'userId'                   => $merchantOrder->user_id,
                'weight'                   => number_format($merchantOrder->weight, 2, '.'),
                'totalAmount'              => $merchantOrder->total_amount,
                'totalAmountWithoutVat'    => $merchantOrder->total_amount_without_vat,
                'totalVatAmount'           => $merchantOrder->total_vat_amount,
                'currency'                 => $merchantOrder->currency,
                'items'                    => $merchantOrder->items,
                'refundedAmount'           => $merchantOrder->refunded_amount,
                'refundedAmountWithoutVat' => $merchantOrder->refunded_amount_without_vat,
                'refundedTotalVatAmount'   => $merchantOrder->refunded_total_vat_amount,
                'refundedTotalQty'         => $merchantOrder->refunded_total_qty,
                'deliveredAt'              => $merchantOrder->delivered_at,
                'refundedAt'               => $merchantOrder->refunded_at,
            ],
        ];
        $this->showWithRoleData($merchantOrder, $expectedData, User::ROLE_MERCHANT);

        $alienOrder = $this->prepareAlienEntity('create', self::$model, ['status_id' => Order::STATUS_PENDING['id']]);
        $this->showWithRoleData($alienOrder, [], User::ROLE_MERCHANT, Response::HTTP_FORBIDDEN);

        $operatorOrder = $this->prepareMerchantEntity('create', self::$model, ['status_id' => Order::STATUS_PENDING['id']]);
        $expectedData  = [
            'type'       => self::$resourceType,
            'id'         => "{$operatorOrder->id}",
            'attributes' => [
                'number'                   => $operatorOrder->number,
                'address'                  => $operatorOrder->address,
                'description'              => $operatorOrder->description,
                'merchantId'               => $operatorOrder->merchant_id,
                'statusId'                 => $operatorOrder->status_id,
                'userId'                   => $operatorOrder->user_id,
                'weight'                   => number_format($operatorOrder->weight, 2, '.'),
                'totalAmount'              => $operatorOrder->total_amount,
                'totalAmountWithoutVat'    => $operatorOrder->total_amount_without_vat,
                'totalVatAmount'           => $operatorOrder->total_vat_amount,
                'currency'                 => $operatorOrder->currency,
                'items'                    => $operatorOrder->items,
                'refundedAmount'           => $operatorOrder->refunded_amount,
                'refundedAmountWithoutVat' => $operatorOrder->refunded_amount_without_vat,
                'refundedTotalVatAmount'   => $operatorOrder->refunded_total_vat_amount,
                'refundedTotalQty'         => $operatorOrder->refunded_total_qty,
                'deliveredAt'              => $operatorOrder->delivered_at,
                'refundedAt'               => $operatorOrder->refunded_at,
            ],
        ];
        $this->showWithRoleData($operatorOrder, $expectedData, User::ROLE_OPERATOR);

        $alienOperatorOrder = $this->prepareAlienEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $this->showWithRoleData(
            $alienOperatorOrder,
            [],
            User::ROLE_OPERATOR,
            Response::HTTP_FORBIDDEN
        );

        $guestOrder = $this->prepareMerchantEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $this->showWithRoleData($guestOrder, [], User::ROLE_GUEST, Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function testUpdate(): void
    {
        $order = $this->prepareMerchantEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );

        $newOrder = $this->prepareMerchantEntity(
            'make',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );

        $data = [
            'type'       => self::$resourceType,
            'id'         => "{$order->id}",
            'attributes' => [
                'number'                   => $newOrder->number,
                'address'                  => $newOrder->address,
                'description'              => $newOrder->description,
                'merchantId'               => $newOrder->merchant_id,
                'statusId'                 => $newOrder->status_id,
                'userId'                   => $newOrder->user_id,
                'weight'                   => $newOrder->weight,
                'totalAmount'              => $newOrder->total_amount,
                'totalAmountWithoutVat'    => $newOrder->total_amount_without_vat,
                'totalVatAmount'           => $newOrder->total_vat_amount,
                'currency'                 => $newOrder->currency,
                'items'                    => $newOrder->items,
                'refundedAmount'           => $newOrder->refunded_amount,
                'refundedAmountWithoutVat' => $newOrder->refunded_amount_without_vat,
                'refundedTotalVatAmount'   => $newOrder->refunded_total_vat_amount,
                'refundedTotalQty'         => $newOrder->refunded_total_qty,
                'deliveredAt'              => $newOrder->delivered_at,
                'refundedAt'               => $newOrder->refunded_at,
            ],
        ];

        $this->updateWithRoleData($order, $data, User::ROLE_ADMIN);

        $this->assertDatabaseHas(self::$resourceType, [
            'number'                      => $newOrder->number,
            'address'                     => $newOrder->address,
            'description'                 => $newOrder->description,
            'merchant_id'                 => $newOrder->merchant_id,
            'status_id'                   => $newOrder->status_id,
            'user_id'                     => $newOrder->user_id,
            'weight'                      => number_format($newOrder->weight, 2, '.'),
            'total_amount'                => $newOrder->total_amount,
            'total_amount_without_vat'    => $newOrder->total_amount_without_vat,
            'total_vat_amount'            => $newOrder->total_vat_amount,
            'currency'                    => $newOrder->currency,
            'refunded_amount'             => $newOrder->refunded_amount,
            'refunded_amount_without_vat' => $newOrder->refunded_amount_without_vat,
            'refunded_total_vat_amount'   => $newOrder->refunded_total_vat_amount,
            'refunded_total_qty'          => $newOrder->refunded_total_qty,
            'delivered_at'                => $newOrder->delivered_at,
            'refunded_at'                 => $newOrder->refunded_at,
        ]);

        $this->updateWithRoleData($order, $data, User::ROLE_MERCHANT);

        $merchantOrder = $this->prepareAlienEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $data['id'] = $merchantOrder->id;
        $this->updateWithRoleData(
            $merchantOrder,
            $data,
            User::ROLE_MERCHANT,
            Response::HTTP_FORBIDDEN
        );

        $operatorOrder = $this->prepareMerchantEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $data['id'] = $operatorOrder->id;
        $this->updateWithRoleData(
            $operatorOrder,
            $data,
            User::ROLE_OPERATOR
        );

        $alienOperatorOrder = $this->prepareAlienEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $data['id'] = $alienOperatorOrder->id;
        $this->updateWithRoleData(
            $alienOperatorOrder,
            $data,
            User::ROLE_OPERATOR,
            Response::HTTP_FORBIDDEN
        );

        $clientOperatorOrder = $this->prepareMerchantEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $data['id'] = $clientOperatorOrder->id;
        $this->updateWithRoleData(
            $clientOperatorOrder,
            $data,
            User::ROLE_CLIENT,
            Response::HTTP_FORBIDDEN
        );

        $guestOperatorOrder = $this->prepareMerchantEntity(
            'create',
            self::$model,
            ['status_id' => Order::STATUS_PENDING['id']]
        );
        $data['id'] = $guestOperatorOrder->id;
        $this->updateWithRoleData(
            $guestOperatorOrder,
            $data,
            User::ROLE_CLIENT,
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function testDestroy(): void
    {
        $order = $this->prepareMerchantEntity('create', self::$model);
        $this->destroyWithRoleData($order, User::ROLE_ADMIN);

        $alienOrder = $this->prepareAlienEntity('create', self::$model);
        $this->destroyWithRoleData($alienOrder, User::ROLE_ADMIN);

        $merchantOrder = $this->prepareMerchantEntity('create', self::$model);
        $this->destroyWithRoleData($merchantOrder, User::ROLE_MERCHANT);

        $alienMerchantOrder = $this->prepareAlienEntity('create', self::$model);
        $this->destroyWithRoleData($alienMerchantOrder, User::ROLE_MERCHANT, Response::HTTP_FORBIDDEN);
        $this->destroyWithRoleData($alienMerchantOrder, User::ROLE_CLIENT, Response::HTTP_FORBIDDEN);
        $this->destroyWithRoleData($alienMerchantOrder, User::ROLE_GUEST, Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function testFilters(): void
    {
        $order = $this->prepareMerchantEntity('create', self::$model);

        $filters = [
            'id'         => "{$order->id}",
            'merchantId' => "{$order->merchant_id}",
            'statusId'   => $order->status_id,
            'userId'     => $order->user_id,
        ];

        $this->doTestFilters($order, $filters);
    }
}

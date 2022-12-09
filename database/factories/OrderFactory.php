<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public const TAX_PERCENT = 20.00;
    public const MOCK_ITEMS  = [
        [
            'title'                 => 'Салфетки бумажные, Plushe, 2 слоя, 230 шт., в ассортименте',
            'description'           => 'Салфетки бумажные, Plushe, станут Вашим помощником в личной гигиене и уборке. Коробка имеет удобную конструкцию, которая позволяет быстро и легко извлечь салфетки, даже когда они заканчиваются. В упаковке: 230 штук. Мягкие двухслойные салфетки подарят ощущение комфорта и чистоты. Состав: 100% первичная целлюлоза. Размер листа: 200х200 мм. Товар представлен в ассортименте.',
            'image'                 => 'https://img.fix-price.com/800x800/images/origin/origin/bf9ab895261d47912a4a7b32d91b2b81.jpg',
            'url'                   => 'https://fix-price.com/catalog/dlya-doma/p-5024542-salfetki-bumajnye-lushe-2-sloya-230-sht-v-assortimente',
            'sku'                   => 'SK-001',
            'baseMeasure'           => 'шт.',
            'totalAmount'           => 24000,
            'totalAmountWithoutVat' => 20000,
            'totalVatAmount'        => 4000,
            'qty'                   => 2,
            'amount'                => 12000,
            'amountWithoutVat'      => 10000,
            'vatAmount'             => 2000,
            'vatPercent'            => self::TAX_PERCENT,
        ],
        [
            'title'                 => 'Набор контейнеров, Phibo, 4 шт.',
            'description'           => 'Набор контейнеров, Phibo – прекрасное решение для хранения готовых блюд и продуктов. Крышки контейнеров украшены яркими новогодними изображениями. Возьмите с собой не только обед или перекус, но и праздничное настроение! В наборе 4 контейнера разных объёмов: 0,3 л, 0,45 л, 0,65 л, 1 л. Подходят для разогревания в микроволновке (только с приоткрытой крышкой). Можно мыть в посудомоечной машине. Состав: полипропилен.',
            'image'                 => 'https://img.fix-price.com/800x800/images/origin/origin/29fe27c855f02a131d569a91944008cf.jpg',
            'url'                   => 'https://fix-price.com/catalog/dlya-doma/p-5023964-nabor-konteynerov-hibo-4-sht',
            'baseMeasure'           => 'шт.',
            'sku'                   => 'SK-002',
            'totalAmount'           => 36000,
            'totalAmountWithoutVat' => 30000,
            'totalVatAmount'        => 6000,
            'qty'                   => 3,
            'amount'                => 12000,
            'amountWithoutVat'      => 10000,
            'vatAmount'             => 2000,
            'vatPercent'            => self::TAX_PERCENT,
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = $this->faker->randomElement(self::MOCK_ITEMS);

        return [
            'id'                          => $this->faker->unique()->uuid(),
            'user_id'                     => User::query()->inRandomOrder()->first()->id,
            'number'                      => 'TEST-' . $this->faker->unique()->numberBetween(0000001, 9999999),
            'currency'                    => '₽',
            'items'                       => [$items],
            'total_amount'                => $items['totalAmount'],
            'total_amount_without_vat'    => $items['totalAmountWithoutVat'],
            'total_vat_amount'            => $items['totalVatAmount'],
            'status_id'                   => Order::STATUS_PENDING['id'],
            'weight'                      => 2.0,
            'delivered_at'                => null,
            'refunded_at'                 => null,
            'refunded_amount'             => 0,
            'refunded_amount_without_vat' => 0,
            'refunded_total_vat_amount'   => 0,
            'refunded_total_qty'          => 0,
            'address'                     => $this->faker->address(),
            'description'                 => $this->faker->sentence(),
            'merchant_id'                 => $this->faker->uuid(),
        ];
    }
}

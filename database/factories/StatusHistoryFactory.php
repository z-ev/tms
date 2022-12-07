<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\StatusHistory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StatusHistory>
 */
class StatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(Order::STATUSES);

        return [
            'id'          => $this->faker->unique()->uuid(),
            'status_id'   => $status['id'],
            'order_id'    => Order::query()->inRandomOrder()->first(),
            'user_id'     => User::query()->inRandomOrder()->first(),
            'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
            'description' => $status['title'],
        ];
    }
}

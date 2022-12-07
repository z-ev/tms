<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create([
            'roles'       => json_encode([User::ROLE_ADMIN]),
            'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
            'status_id'   => User::STATUS_ACTIVE,
        ]);
        User::factory()->create([
            'roles'       => json_encode([User::ROLE_MERCHANT]),
            'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
            'status_id'   => User::STATUS_ACTIVE,
        ]);
        User::factory()->create([
            'roles'       => json_encode([User::ROLE_OPERATOR]),
            'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
            'status_id'   => User::STATUS_ACTIVE,
        ]);
        User::factory()->create([
            'roles'       => json_encode([User::ROLE_CLIENT]),
            'merchant_id' => DatabaseSeeder::MOCK_MERCHANT_ID,
            'status_id'   => User::STATUS_ACTIVE,
        ]);
    }
}

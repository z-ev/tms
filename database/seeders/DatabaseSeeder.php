<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public const MOCK_MERCHANT_ID   = 'c34cf7a9-b5a7-376a-967a-2866dd19f1d3';
    public const MOCK_MERCHANT_NAME = 'ООО "ФИКСПРАЙС ТРЕЙД"';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MerchantSeeder::class,
            OrderSeeder::class,
        ]);
    }
}

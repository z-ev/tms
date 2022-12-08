<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merchant::factory(1)->create(
            [
                'id'   => DatabaseSeeder::MOCK_MERCHANT_ID,
                'name' => DatabaseSeeder::MOCK_MERCHANT_NAME,
            ]
        );
        Merchant::factory(9)->create();
    }
}

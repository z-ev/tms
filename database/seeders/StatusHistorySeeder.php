<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StatusHistory;
use Illuminate\Database\Seeder;

class StatusHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StatusHistory::factory(50)->create();
    }
}

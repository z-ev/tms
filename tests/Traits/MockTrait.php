<?php
/*
 * Copyright © 2022 Z-EV.
 * All rights reserved.
 */

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Facades\Bus;

trait MockTrait
{
    /**
     * @return void
     */
    public function createMocks(): void
    {
        Bus::fake();
    }
}
